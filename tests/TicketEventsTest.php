<?php

use Illuminate\Support\Facades\Event;
use Sgcomptech\FilamentTicketing\Events\NewAssignment;
use Sgcomptech\FilamentTicketing\Events\NewComment;
use Sgcomptech\FilamentTicketing\Events\NewTicket;
use Sgcomptech\FilamentTicketing\Events\NewResponse;
use Sgcomptech\FilamentTicketing\Filament\Resources\TicketResource\Pages\CreateTicket;
use Sgcomptech\FilamentTicketing\Filament\Resources\TicketResource\Pages\EditTicket;
use Sgcomptech\FilamentTicketing\Filament\Resources\TicketResource\RelationManagers\CommentsRelationManager;
use Sgcomptech\FilamentTicketing\Models\Ticket;
use Sgcomptech\FilamentTicketing\Tests\User;

use function Pest\Livewire\livewire;

beforeEach(function () {
	Event::fake();
	$this->user = User::factory()->create();
	$this->actingAs($this->user);
	$this->title = fake()->words(5, true);
	livewire(CreateTicket::class)
		->fillForm([
			'title' => $this->title,
			'content' => 'fake content',
			'priority' => 0,
		])
		->call('create');
	$this->ticket = Ticket::where('title', $this->title)->first();
});

it('New ticket event', function () {
	Event::assertDispatched(function (NewTicket $event) {
		return $event->ticket->title === $this->title;
	});
});

it('New comment event', function () {
	livewire(CommentsRelationManager::class, ['ownerRecord' => $this->ticket])
			->mountTableAction('addComment')
			->setTableActionData([
				'content' => $comment = fake()->words(10, true),
			])
			->callMountedTableAction();

	Event::assertDispatched(fn (NewComment $event) => $event->comment->content === $comment);
});

it('New response event', function () {
	$support = User::factory()->create();
	$this->actingAs($support);
	livewire(CommentsRelationManager::class, ['ownerRecord' => $this->ticket])
			->mountTableAction('addComment')
			->setTableActionData([
				'content' => $comment = fake()->words(10, true),
			])
			->callMountedTableAction();

	Event::assertDispatched(fn (NewResponse $event) => $event->response->content === $comment);
});

it('New assignment event', function () {
	$manager = User::factory()->create();
	$support = User::factory()->create();
	$this->actingAs($manager);
	livewire(EditTicket::class, ['record' => $this->ticket->id])
		->fillForm([
			'assigned_to_id' => $support->id,
			'status' => 1,
		])
		->call('save')
		->assertHasNoFormErrors();

	Event::assertDispatched(fn (NewAssignment $event) => $event->ticket->assigned_to_id === $support->id);
	Event::assertDispatched(NewAssignment::class, 1);

	livewire(EditTicket::class, ['record' => $this->ticket->id])
		->fillForm([
			'status' => 2,
		])
		->call('save')
		->assertHasNoFormErrors();

	Event::assertDispatchedTimes(NewAssignment::class, 1); // no new assignment

	livewire(EditTicket::class, ['record' => $this->ticket->id])
		->fillForm([
			'assigned_to_id' => $manager->id,
			'status' => 3,
		])
		->call('save')
		->assertHasNoFormErrors();

	Event::assertDispatched(NewAssignment::class, 2);
	Event::assertDispatched(fn (NewAssignment $event) => $event->ticket->assigned_to_id === $manager->id);
});