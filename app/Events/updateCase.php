<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class updateCase implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('updateCase');
    }

        /**

     * The event's broadcast name.

     *

     * @return string

     */

    public function broadcastAs()

    {

        return 'updateCase';

    }

        /**

     * The event's broadcast name.

     *

     * @return string

     */

    public function broadcastWith()

    {

        return ['id'=>$this->id];

    }
}
