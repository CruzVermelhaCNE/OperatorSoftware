<?php

namespace App\Events;

use App\COVID19Ambulance;
use Illuminate\Queue\SerializesModels;

class COVID19AmbulanceSaved
{
    use SerializesModels;

    public $ambulance;

    /**
     * Create a new event instance.
     *
     * @param  \App\COVID19Ambulance  $case
     * @return void
     */
    public function __construct(COVID19Ambulance $ambulance)
    {
        $this->ambulance = $ambulance;
    }
}