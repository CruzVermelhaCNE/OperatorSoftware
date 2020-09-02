<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class COVID19CaseObservation extends Model
{
    use SoftDeletes;

    protected $table = 'covid19_case_observations';

    public function case()
    {
        return $this->hasOne(COVID19Case::class);
    }

    public function author()
    {
        return $this->hasOne(User::class, 'id', 'author_id');
    }

    public function deleted_by()
    {
        return $this->hasOne(User::class, 'id', 'deleted_by');
    }

    public static function createCaseObservation($case_id, $author_id, $observation)
    {
        $case_observation                 = new COVID19CaseObservation();
        $case_observation->case_id        = $case_id;
        $case_observation->author_id      = $author_id;
        $case_observation->observation    = $observation;
        $case_observation->deleted_by     = null;
        $case_observation->reason_deleted = null;
        $case_observation->save();
    }

    public function remove($deleted_by)
    {
        $this->deleted_by = $deleted_by;
        $this->save();
        $this->delete();
    }
}
