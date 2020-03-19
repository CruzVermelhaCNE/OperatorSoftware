<?php
declare(strict_types=1);

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class COVID19UpdateAmbulance implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $ambulance;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($ambulance)
    {
        $this->ambulance = $ambulance;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('COVID19UpdateAmbulance');
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return ['ambulance' => $this->ambulance];
    }
}
