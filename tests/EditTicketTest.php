<?php

use Illuminate\Support\Facades\DB;
use function Pest\Livewire\livewire;
use Sgcomptech\FilamentTicketing\Filament\Resources\TicketResource\Pages\CreateTicket;
use Sgcomptech\FilamentTicketing\Filament\Resources\TicketResource\Pages\EditTicket;
use Sgcomptech\FilamentTicketing\Models\Ticket;
use Sgcomptech\FilamentTicketing\Tests\Item;
use Sgcomptech\FilamentTicketing\Tests\User;

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
	 (id, identifier,  ticketable_type, ticketable_id, user_id, assigned_to_id, status, priority,      title,     content,   created_at, updated_at) values
	  (1, '1',                    null,          null, $userA->id,           null,      0,        0, 'Ticket 1', 'Content 1', '2022-10-10 00:00:00', '2022-10-10 00:00:00'),
	  (2, '2',                    null,          null, $userA->id,   $manager->id,      0,        0, 'Ticket 2', 'Content 2', '2022-10-10 00:00:00', '2022-10-10 00:00:00'),
	  (3, '3',                    null,          null, $userB->id,  $supportA->id,      3,        0, 'Ticket 3', 'Content 3', '2022-10-10 00:00:00', '2022-10-10 00:00:00'),
	  (4, '4',                    null,          null, $userB->id,  $supportB->id,      2,        0, 'Ticket 4', 'Content 4', '2022-10-10 00:00:00', '2022-10-10 00:00:00');
	");

    $this->actingAs($admin);

    livewire(EditTicket::class, ['record' => 2])
        ->assertFormFieldExists('status')
        ->assertFormFieldIsEnabled('status');
    // ->assertFormFieldIsDisabled('status');

    $this->actingAs($manager);
    livewire(EditTicket::class, ['record' => 3])->assertFormFieldIsEnabled('status');

    $this->actingAs($supportA);
    livewire(EditTicket::class, ['record' => 3])->assertFormFieldIsEnabled('status');
    livewire(EditTicket::class, ['record' => 4])->assertFormFieldIsDisabled('status');

    $this->actingAs($supportB);
    livewire(EditTicket::class, ['record' => 4])->assertFormFieldIsEnabled('status');
    livewire(EditTicket::class, ['record' => 3])->assertFormFieldIsDisabled('status');

    $this->actingAs($userA);
    livewire(EditTicket::class, ['record' => 1])->assertFormFieldIsDisabled('status');
    livewire(EditTicket::class, ['record' => 2])->assertFormFieldIsDisabled('status');

    $this->actingAs($userB);
    livewire(EditTicket::class, ['record' => 3])->assertFormFieldIsDisabled('status');
    livewire(EditTicket::class, ['record' => 4])->assertFormFieldIsDisabled('status');
});

it('tickets with association', function () {
    $user = User::factory()->create();
    $item = Item::factory()->create();

    $this->be($user);
    livewire(CreateTicket::class, ['rec' => $item->model_class(), 'recid' => $item->id])
        ->fillForm([
            'title' => $title = fake()->words(5, true),
            'content' => fake()->words(10, true),
            'priority' => 0,
        ])
        ->call('create');
    $ticket = Ticket::where('title', $title)->firstOrFail();

    livewire(EditTicket::class, ['record' => $ticket->id])
        ->assertSee("[{$item->name}]");
});
