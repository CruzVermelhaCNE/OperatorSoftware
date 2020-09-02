<?php

namespace App\Events;

use App\Models\COVID19Case;
use Illuminate\Queue\SerializesModels;

class COVID19CaseDeleted
{
    use SerializesModels;

    public $case;

    /**
     * Create a new event instance.
     *
     * @param  \App\COVID19Case  $case
     * @return void
     */
    public function __construct(COVID19Case $case)
    {
        $this->case = $case;
    }
}