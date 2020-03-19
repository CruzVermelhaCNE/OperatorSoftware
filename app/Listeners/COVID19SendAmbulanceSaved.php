<?php
declare(strict_types=1);

namespace App\Listeners;

use App\Events\COVID19AmbulanceSaved;
use App\Events\COVID19UpdateAmbulance;

class COVID19SendAmbulanceSaved
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(COVID19AmbulanceSaved $event)
    {
        event(new COVID19UpdateAmbulance($event->ambulance));
    }
}
