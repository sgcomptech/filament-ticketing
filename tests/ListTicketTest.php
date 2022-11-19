<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Sgcomptech\FilamentTicketing\Filament\Resources\TicketResource\Pages\ListTicket;
use Sgcomptech\FilamentTicketing\Tests\User;

use function Pest\Livewire\livewire;

it('List tickets with authorization', function () {
	config(['filament-ticketing.use_authorization' => true]);
	$superUser = User::factory()->create(['id' => 1]);
	$admin = User::factory()->create(['name' => 'Administrator']);
	$manager = User::factory()->create(['name' => 'Manager']);
	$supportA = User::factory()->create(['name' => 'Support A']);
	$supportB = User::factory()->create(['name' => 'Support B']);
	$userA = User::factory()->create(['name' => 'User A']);
	$userB = User::factory()->create(['name' => 'User B']);

	DB::insert("insert into tickets
	 (id, identifier, ticketable_type, ticketable_id, user_id, assigned_to_id, status, priority,      title, content) values
	  (1, '1',                   null,          null, $userA->id,           null,      0,        0, 'Ticket 1', 'Content 1'),
	  (2, '2',      'App\Models\Item',             1, $userA->id,   $manager->id,      0,        0, 'Ticket 2', 'Content 2'),
	  (3, '3',      'App\Models\Item',             1, $userB->id,  $supportA->id,      3,        0, 'Ticket 3', 'Content 3'),
	  (4, '4',      'App\Models\Item',             2, $userB->id,  $supportB->id,      2,        0, 'Ticket 4', 'Content 4');
	");

	$this->actingAs($admin);

	/// List all
	livewire(ListTicket::class)
		->assertSee(['Ticket 1', 'Ticket 2', 'Ticket 3', 'Ticket 4']);

	$this->actingAs($manager);

	livewire(ListTicket::class)
		->assertSee(['Ticket 1', 'Ticket 2', 'Ticket 3', 'Ticket 4']);
	
	$this->actingAs($supportA);

	livewire(ListTicket::class)
		->assertSee('Ticket 3')
		->assertDontSee(['Ticket 1', 'Ticket 2', 'Ticket 4']);

	$this->actingAs($supportB);

	livewire(ListTicket::class)
		->assertSee('Ticket 4')
		->assertDontSee(['Ticket 1', 'Ticket 2', 'Ticket 3']);
	
	$this->actingAs($userA);

	livewire(ListTicket::class)
		->assertSee(['Ticket 1', 'Ticket 2'])
		->assertDontSee(['Ticket 3', 'Ticket 4']);

	$this->actingAs($userB);

	livewire(ListTicket::class)
		->assertSee(['Ticket 3', 'Ticket 4'])
		->assertDontSee(['Ticket 1', 'Ticket 2']);
});