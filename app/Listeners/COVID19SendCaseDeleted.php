<?php
declare(strict_types=1);

namespace App\Listeners;

use App\Events\COVID19CaseDeleted;
use App\Events\COVID19DeleteCase;

class COVID19SendCaseDeleted
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
    public function handle(COVID19CaseDeleted $event)
    {
        event(new COVID19DeleteCase($event->case));
    }
}
