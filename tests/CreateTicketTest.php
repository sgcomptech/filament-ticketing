<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use SGCompTech\FilamentTicketing\Filament\Resources\TicketResource;
use SGCompTech\FilamentTicketing\Filament\Resources\TicketResource\Pages\CreateTicket;
use SGCompTech\FilamentTicketing\Tests\User;
use function Pest\Livewire\livewire;

function tableDump(string $table = null)
{
	if ($table) {
		echo "=== $table ===\n";
		foreach (DB::select("select * from $table") as $item) {
			echo json_encode($item) . "\n";
		}
	} else {
		echo "=== tables ===\n";
		foreach (DB::select("select name from sqlite_master") as $table) {
			echo "$table->name\n";
		}
	}
}

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