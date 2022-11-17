<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Sgcomptech\FilamentTicketing\Filament\Resources\TicketResource\Pages\CreateTicket;
use Sgcomptech\FilamentTicketing\Tests\Item;
use Sgcomptech\FilamentTicketing\Tests\User;

use function Pest\Livewire\livewire;

it('ticket requires title and content', function () {
	$user = User::factory()->create();
	/** @var mixed $user */
	$this->actingAs($user);
	$title = 'Test title';
	livewire(CreateTicket::class)
		->assertSee($user->name)
		->fillForm([
			'title' => '',
			'content' => 'fake content',
			'priority' => 0,
		])
		->call('create')
		->assertHasFormErrors();

	livewire(CreateTicket::class)
		->fillForm([
			'title' => 'fake title',
			'content' => '',
			'priority' => 0,
		])
		->call('create')
		->assertHasFormErrors();

	livewire(CreateTicket::class)
		->fillForm([
			'title' => $title,
			'content' => 'fake content',
			'priority' => 0,
		])
		->call('create')
		->assertHasNoFormErrors();

	$this->assertDatabaseHas('tickets', [
		'name' => null,
		'email' => null,
		'title' => $title,
		'ticketable_type' => null,
		'ticketable_id' => null,
	]);
});

it('not using User model', function () {
	config(['filament-ticketing.user-model' => null]);
	$user = User::factory()->create();
	/** @var mixed $user */
	$this->actingAs($user);
	livewire(CreateTicket::class)
		->assertDontSee($user->name)
		->assertDontSee($user->email)
		->fillForm([
			'name' => '',
			'email' => '',
			'title' => 'fake title',
			'content' => 'fake content',
			'priority' => 0,
		])
		->call('create')
		->assertHasFormErrors();

	$name = 'Test Name';
	$title = 'Test Title';
	livewire(CreateTicket::class)
		->assertDontSee($user->name)
		->assertDontSee($user->email)
		->fillForm([
			'name' => $name,
			'email' => 'fake@email',
			'title' => $title,
			'content' => 'fake content',
			'priority' => 0,
		])
		->call('create')
		->assertHasNoFormErrors();

	$this->assertDatabaseHas('tickets', [
		'name' => $name,
		'title' => $title,
		'ticketable_type' => null,
		'ticketable_id' => null,
	]);
});

it('not attached to any model', function () {
	config(['filament-ticketing.user-model' => null]);
	$user = User::factory()->create();
	/** @var mixed $user */
	$this->actingAs($user);
	$title = 'fake title';
	livewire(CreateTicket::class)
		->fillForm([
			'name' => 'fake name',
			'email' => 'fake@email',
			'title' => $title,
			'content' => 'fake content',
			'priority' => 0,
		])
		->call('create')
		->assertHasNoFormErrors();

	$this->assertDatabaseHas('tickets', [
		'title' => $title,
		'ticketable_type' => null,
		'ticketable_id' => null,
	]);
});

it('attached to model', function () {
	$user = User::factory()->create();
	/** @var mixed $user */
	$this->actingAs($user);
	$itemA = Item::factory()->create();
	$itemB = Item::factory()->create();

	$title = 'fake title';
	livewire(CreateTicket::class, ['rec' => $itemA->model_class(), 'recid' => $itemA->id])
		->fillForm([
			'title' => $title,
			'content' => 'fake content',
			'priority' => 0,
		])
		->call('create')
		->assertHasNoFormErrors();

	$this->assertDatabaseHas('tickets', [
		'title' => $title,
		'ticketable_type' => $itemA->model_class(),
		'ticketable_id' => $itemA->id,
	]);
});