<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Events\COVID19CaseSaved;
use App\Events\COVID19UpdateCase;

class COVID19SendCaseSaved
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
    public function handle(COVID19CaseSaved $event)
    {
        event(new COVID19UpdateCase($event->case->id));
    }
}
