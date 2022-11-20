<?php

use Sgcomptech\FilamentTicketing\Filament\Resources\TicketResource\Pages\EditTicket;
use Sgcomptech\FilamentTicketing\Models\Ticket;
use Sgcomptech\FilamentTicketing\Tests\User;

use function Pest\Livewire\livewire;

it('Assign user to ticket', function () {
	config(['filament-ticketing.use_authorization' => true]);
	$superUser = User::factory()->create(['id' => 1]);
	$admin = User::factory()->create(['name' => 'Administrator']);
	$manager = User::factory()->create(['name' => 'Manager']);
	$support = User::factory()->create(['name' => 'Support A']);
	$user = User::factory()->create(['name' => 'User A']);

	$ticket = Ticket::create([
		'identifier' => '1',
		'user_id' => $user->id,
		'assigned_to_id' => $support->id,
		'status' => 0,
		'priority' => 0,
		'title' => fake()->words(5, true),
		'content' => fake()->words(10, true),
	]);

	$this->actingAs($admin);
	livewire(EditTicket::class, ['record' => $ticket->id])
		->assertFormFieldExists('assigned_to_id');

	$this->actingAs($manager);
	livewire(EditTicket::class, ['record' => $ticket->id])
		->assertFormFieldExists('assigned_to_id');

	$this->actingAs($support);
	livewire(EditTicket::class, ['record' => $ticket->id])
		->assertFormFieldIsDisabled('assigned_to_id');

	$this->actingAs($user);
	livewire(EditTicket::class, ['record' => $ticket->id])
		->assertFormFieldIsDisabled('assigned_to_id');
});