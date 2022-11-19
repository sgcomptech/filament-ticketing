<?php
return [
	'user-model' => \App\Models\User::class,

	'resources' => [
		Sgcomptech\FilamentTicketing\Filament\Resources\TicketResource::class,
		Sgcomptech\FilamentTicketing\Filament\Resources\CommentResource::class,
	],

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
];
