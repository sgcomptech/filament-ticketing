<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Sgcomptech\FilamentTicketing\Filament\Resources\TicketResource\Pages\EditTicket;
use Sgcomptech\FilamentTicketing\Models\Ticket;
use Sgcomptech\FilamentTicketing\Tests\User;

use function Pest\Livewire\livewire;

it('Update tickets with authorization', function () {
	config(['filament-ticketing.use_authorization' => true]);
	$superUser = User::factory()->create(['id' => 1]);
	$admin = User::factory()->create(['name' => 'Administrator']);
	$manager = User::factory()->create(['name' => 'Manager']);
	$supportA = User::factory()->create(['name' => 'Support A']);
	$supportB = User::factory()->create(['name' => 'Support B']);
	$userA = User::factory()->create(['name' => 'User A']);
	$userB = User::factory()->create(['name' => 'User B']);

	DB::insert("insert into tickets
	 (id,   ticketable_type, ticketable_id, name, email,    user_id, assigned_to_id, status, priority,      title, content) values
	  (1,              null,          null, null,  null, $userA->id,           null,      0,        0, 'Ticket 1', 'Content 1'),
	  (2, 'App\Models\Item',             1, null,  null, $userA->id,   $manager->id,      0,        0, 'Ticket 2', 'Content 2'),
	  (3, 'App\Models\Item',             1, null,  null, $userB->id,  $supportA->id,      3,        0, 'Ticket 3', 'Content 3'),
	  (4, 'App\Models\Item',             2, null,  null, $userB->id,  $supportB->id,      2,        0, 'Ticket 4', 'Content 4');
	");

	$this->actingAs($admin);

	livewire(EditTicket::class, ['record' => 1])
		->assertFormFieldIsEnabled('status');
});