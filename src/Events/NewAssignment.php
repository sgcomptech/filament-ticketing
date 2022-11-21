<?php

namespace Sgcomptech\FilamentTicketing\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Sgcomptech\FilamentTicketing\Models\Ticket;

class NewAssignment
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
		 * @param Sgcomptech\FilamentTicketing\Models\Ticket $ticket
     * @return void
     */
    public function __construct(public Ticket $ticket)
    {
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel(config('filament-ticketing.event_broadcast_channel'));
    }
}