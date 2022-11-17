<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Sgcomptech\FilamentTicketing\Filament\Resources\TicketResource\Pages\CreateTicket;
use Sgcomptech\FilamentTicketing\Filament\Resources\TicketResource\Pages\ListTicket;
use Sgcomptech\FilamentTicketing\Tests\User;

use function Pest\Livewire\livewire;

it('List tickets', function () {
	$superUser = User::findOrFail(1); // super admin
	$manager = User::factory()->create();
	$supportA = User::factory()->create();
	$supportB = User::factory()->create();

	DB::insert("insert into tickets
	 (id,   ticketable_type, ticketable_id, name, email, user_id, assigned_to_id, status, priority,      title, content) values
	  (1,              null,          null, null,  null,       1,           null,      0,        0, 'Ticket 1', 'Content 1'),
	  (2, 'App\Models\Item',             1, null,  null,       1,   $manager->id,      0,        0, 'Ticket 2', 'Content 2'),
	  (3, 'App\Models\Item',             1, null,  null,       1,  $supportA->id,      3,        0, 'Ticket 3', 'Content 3'),
	  (4, 'App\Models\Item',             2, null,  null,       1,  $supportB->id,      2,        0, 'Ticket 4', 'Content 4');
	");

	/** @var mixed $superUser */
	$this->actingAs($superUser);

	/// List all
	livewire(ListTicket::class)
		->assertSee(['Ticket 1', 'Ticket 2', 'Ticket 3', 'Ticket 4']);

	
});