<?php

namespace Sgcomptech\FilamentTicketing\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Sgcomptech\FilamentTicketing\Models\Comment;

class NewComment
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  Sgcomptech\FilamentTicketing\Models\Comment  $comment
     * @return void
     */
    public function __construct(public Comment $comment)
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
