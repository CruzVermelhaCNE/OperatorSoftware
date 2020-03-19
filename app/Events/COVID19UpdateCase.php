<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class COVID19UpdateCase implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $case;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($case)
    {
        $this->case = $case;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('updateCase');
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()

    {
        return ['case'=>$this->case];

    }
}