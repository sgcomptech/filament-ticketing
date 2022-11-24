<?php

return [
    'user-model' => \App\Models\User::class,

    // You can extend the package's TicketResource to customize to your needs.
    'ticket-resource' => Sgcomptech\FilamentTicketing\Filament\Resources\TicketResource::class,

    // whether a ticket must be strictly interacted with another model
    'is_strictly_interacted' => false,

    // filament navigation
    'navigation' => [
        'group' => 'Tickets',
        'sort' => 1,
    ],

    // ticket statuses
    'statuses' => [
        0 => 'Open',
        1 => 'Pending',
        2 => 'Resolved',
        2 => 'Closed',
    ],

    // ticket priorities
    'priorities' => [
        0 => 'Low',
        1 => 'Normal',
        2 => 'High',
        3 => 'Critical',
    ],

    // use authorization
    'use_authorization' => false,

    // event broadcast channel
    'event_broadcast_channel' => 'ticket-channel',
];
