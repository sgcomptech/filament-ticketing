<?php
return [
	// set to 'null' if ticket should not be related to any user model
	'user-model' => \App\Models\User::class,

	'resources' => [
		SGCompTech\FilamentTicketing\Filament\Resources\TicketResource::class,
		// SGCompTech\FilamentTicketing\Filament\Resources\CommentResource::class,
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
];
