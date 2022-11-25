<?php

use Illuminate\Support\Facades\DB;
use function Pest\Livewire\livewire;
use Sgcomptech\FilamentTicketing\Filament\Resources\TicketResource\Pages\CreateTicket;
use Sgcomptech\FilamentTicketing\Filament\Resources\TicketResource\Pages\ListTicket;
use Sgcomptech\FilamentTicketing\Tests\Item;
use Sgcomptech\FilamentTicketing\Tests\User;

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
	  (1, '1',                   null,          null, $userA->id,           null,      1,        1, 'Ticket 1', 'Content 1'),
	  (2, '2',      'App\Models\Item',             1, $userA->id,   $manager->id,      1,        1, 'Ticket 2', 'Content 2'),
	  (3, '3',      'App\Models\Item',             1, $userB->id,  $supportA->id,      3,        1, 'Ticket 3', 'Content 3'),
	  (4, '4',      'App\Models\Item',             2, $userB->id,  $supportB->id,      2,        1, 'Ticket 4', 'Content 4');
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

it('List correct tickets for interacted model', function () {
    config(['filament-ticketing.use_authorization' => true]);
    $superUser = User::factory()->create(['id' => 1]);
    $admin = User::factory()->create(['name' => 'Administrator']);
    $manager = User::factory()->create(['name' => 'Manager']);
    $support = User::factory()->create(['name' => 'Support A']);
    $userA = User::factory()->create(['name' => 'User A']);
    $userB = User::factory()->create(['name' => 'User B']);

    $itemA = Item::factory()->create(['name' => 'Item A']);
    $itemB = Item::factory()->create(['name' => 'Item B']);
    $itemC = Item::factory()->create(['name' => 'Item C']);

    $this->actingAs($userA);

    $title1 = 'fake title 1';
    livewire(CreateTicket::class, [
        'rec' => $itemA->model_class(),
        'recid' => $itemA->id, ])
        ->fillForm([
            'title' => $title1,
            'content' => 'fake content',
            'priority' => 1,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $title2 = 'fake title 2';
    livewire(CreateTicket::class, [
        'rec' => $itemB->model_class(),
        'recid' => $itemB->id, ])
        ->fillForm([
            'title' => $title2,
            'content' => 'fake content',
            'priority' => 1,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->actingAs($userB);

    $title3 = 'fake title 3';
    livewire(CreateTicket::class, [
        'rec' => $itemC->model_class(),
        'recid' => $itemC->id, ])
        ->fillForm([
            'title' => $title3,
            'content' => 'fake content',
            'priority' => 1,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    livewire(ListTicket::class)
        ->assertSee([$title3])
        ->assertDontSee([$title1, $title2, $itemA->name, $itemB->name, $itemC->name]);

    livewire(ListTicket::class, ['rec' => $itemC->model_class(), 'recid' => $itemC->id])
        ->assertSee([$title3, $itemC->name])
        ->assertDontSee([$title1, $title2, $itemA->name, $itemB->name]);

    livewire(ListTicket::class, ['rec' => $itemB->model_class(), 'recid' => $itemB->id])
        ->assertSee([$itemB->name])
        ->assertDontSee([$title1, $title2, $title3]);

    $this->actingAs($userA);

    livewire(ListTicket::class)
        ->assertSee([$title1, $title2])
        ->assertDontSee([$title3]);

    livewire(ListTicket::class, ['rec' => $itemA->model_class(), 'recid' => $itemA->id])
        ->assertSee([$title1])
        ->assertDontSee([$title2, $title3]);

    livewire(ListTicket::class, ['rec' => $itemB->model_class(), 'recid' => $itemB->id])
        ->assertSee([$title2])
        ->assertDontSee([$title1, $title3]);

    livewire(ListTicket::class, ['rec' => $itemC->model_class(), 'recid' => $itemC->id])
        ->assertDontSee([$title1, $title2, $title3]);
});

it('strictly interacted test', function () {
    $user = User::factory()->create(['name' => 'User']);
    $this->be($user);
    livewire(ListTicket::class)
        ->assertSee('New ticket');

    config(['filament-ticketing.is_strictly_interacted' => true]);
    livewire(ListTicket::class)
        ->assertDontSee('New ticket');

    $item = Item::factory()->create();
    livewire(ListTicket::class, ['rec' => $item->model_class(), 'recid' => $item->id])
        ->assertSee('New ticket');
});
