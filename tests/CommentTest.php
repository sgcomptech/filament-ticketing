<?php

use Sgcomptech\FilamentTicketing\Filament\Resources\TicketResource\Pages\CreateTicket;
use Sgcomptech\FilamentTicketing\Filament\Resources\TicketResource\Pages\EditTicket;
use Sgcomptech\FilamentTicketing\Filament\Resources\TicketResource\RelationManagers\CommentsRelationManager;
use Sgcomptech\FilamentTicketing\Models\Ticket;
use Sgcomptech\FilamentTicketing\Tests\Item;
use Sgcomptech\FilamentTicketing\Tests\User;

use function Pest\Livewire\livewire;

beforeEach(function () {
	config(['filament-ticketing.use_authorization' => true]);
	$this->admin = User::factory()->create(['id' => 1, 'name' => 'Super Admin']);
	$this->manager = User::factory()->create(['name' => 'Manager']);
	$this->supportA = User::factory()->create(['name' => 'Support A']);
	$this->supportB = User::factory()->create(['name' => 'Support B']);
	$this->userA = User::factory()->create(['name' => 'User A']);
	$this->userB = User::factory()->create(['name' => 'User B']);
	$item = Item::factory()->create();

	$this->be($this->userA);
	$title = fake()->words(5, true);
	livewire(CreateTicket::class, ['rec' => $item->model_class(), 'recid' => $item->id])
		->fillForm([
			'title' => $title,
			'content' => fake()->words(10, true),
			'priority' => 0,
		])
		->call('create');
	$this->ticket = Ticket::where('title', $title)->firstOrFail();
	$this->ticket->assigned_to_id = $this->supportA->id;
	$this->ticket->save();
});

it('Create comment', function () {
	$this->be($this->userA);
	livewire(CommentsRelationManager::class, ['ownerRecord' => $this->ticket])
			->mountTableAction('addComment')
			->setTableActionData([
				'content' => $comment = fake()->words(10, true),
			])
			->callMountedTableAction()
			->assertHasNoTableActionErrors();

	$this->assertDatabaseHas('comments', [
		'content' => $comment,
		'user_id' => $this->userA->id,
		'ticket_id' => $this->ticket->id,
	]);
});

it('Other user cannot create comment', function () {
	$this->actingAs($this->userB);
	livewire(CommentsRelationManager::class, ['ownerRecord' => $this->ticket])
			->mountTableAction('addComment')
			->setTableActionData([
				'content' => $comment = fake()->words(10, true),
			])
			->callMountedTableAction()
			->assertForbidden();

	$this->assertDatabaseMissing('comments', [
		'content' => $comment,
	]);
});

it('Manager can create comment', function () {
	$this->actingAs($this->manager);
	livewire(CommentsRelationManager::class, ['ownerRecord' => $this->ticket])
			->mountTableAction('addComment')
			->setTableActionData([
				'content' => $comment = fake()->words(10, true),
			])
			->callMountedTableAction()
			->assertHasNoTableActionErrors();

	$this->assertDatabaseHas('comments', [
		'content' => $comment,
		'user_id' => $this->manager->id,
		'ticket_id' => $this->ticket->id,
	]);
});

it('Assigned support can create comment', function () {
	$this->actingAs($this->supportA);
	livewire(CommentsRelationManager::class, ['ownerRecord' => $this->ticket])
			->mountTableAction('addComment')
			->setTableActionData([
				'content' => $comment = fake()->words(10, true),
			])
			->callMountedTableAction()
			->assertHasNoTableActionErrors();

	$this->assertDatabaseHas('comments', [
		'content' => $comment,
		'user_id' => $this->supportA->id,
		'ticket_id' => $this->ticket->id,
	]);
});

it('Unassigned support cannot create comment', function () {
	$this->actingAs($this->supportB);
	livewire(CommentsRelationManager::class, ['ownerRecord' => $this->ticket])
			->mountTableAction('addComment')
			->setTableActionData([
				'content' => $comment = fake()->words(10, true),
			])
			->callMountedTableAction()
			->assertForbidden();

	$this->assertDatabaseMissing('comments', [
		'content' => $comment,
	]);
});