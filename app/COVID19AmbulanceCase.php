<?php
declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class COVID19AmbulanceCase extends Model
{
    use SoftDeletes;
    protected $table = 'covid19_ambulance_cases';

    public static function createAmbulanceCase($ambulance_id, $case_id)
    {
        $ambulance_case               = new COVID19AmbulanceCase();
        $ambulance_case->ambulance_id = $ambulance_id;
        $ambulance_case->case_id      = $case_id;
        $ambulance_case->save();
    }
}
