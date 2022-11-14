<?php

use Illuminate\Support\Facades\Event;
use SGCompTech\FilamentTicketing\Tests\User;
use SGCompTech\FilamentTicketing\Filament\Resources\TicketResource;
use SGCompTech\FilamentTicketing\Filament\Resources\TicketResource\Pages\CreateTicket;
use function Pest\Livewire\livewire;

/*
$user = null;
beforeEach(function () {
	global $user;
	$user = User::factory()->create();
});
*/

it('ticket requires title and content', function () {
	$user = User::factory()->create();
	/** @var mixed $user */
	$this->actingAs($user);
	livewire(CreateTicket::class)
		->fillForm([
			'title' => '',
			'content' => 'fake content',
		])
		->call('create')
		->assertHasFormErrors();
});
