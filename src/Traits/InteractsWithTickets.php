<?php

namespace SGCompTech\FilamentTicketing\Traits;

use SGCompTech\FilamentTicketing\Models\Ticket;

trait InteractsWithTickets
{
	public function model_class(): string
	{
		return __CLASS__;
	}

	public function model_name(): string
	{
		return 'name';
	}

	public function tickets()
	{
		return $this->morphMany(Ticket::class, 'ticketable');
	}

	public function latestTicket()
	{
		return $this->morphOne(Ticket::class, 'ticketable')->latestOfMany();
	}
}