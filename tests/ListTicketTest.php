<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Sgcomptech\FilamentTicketing\Filament\Resources\TicketResource\Pages\CreateTicket;
use Sgcomptech\FilamentTicketing\Tests\Item;
use Sgcomptech\FilamentTicketing\Tests\User;

use function Pest\Livewire\livewire;

it('List tickets', function () {
	$user = User::factory()->create();
	/** @var mixed $user */
	$this->actingAs($user);

	DB::insert("insert into tickets (id,ticketable_type,ticketable_id,name,email,user_id,status,priority,title,content) values
	  (1, null, null, null, null, 1, 0, 0, 'Ticket 1', 'Content 1'),
	  (2, 'App\Models\Item', 1, null, null, 1, 0, 0, 'Ticket 2', 'Content 2');
	");
});