<?php

namespace Sgcomptech\FilamentTicketing\Traits;

use Sgcomptech\FilamentTicketing\Models\Ticket;

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

    public function getNameAttribute()
    {
        return $this->{$this->model_name()};
    }
}
