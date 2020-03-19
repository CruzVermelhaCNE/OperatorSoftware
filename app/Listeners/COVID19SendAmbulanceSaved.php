<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
        event(new COVID19SendAmbulanceSaved($event->case->id));
    }
}
