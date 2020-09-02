<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class COVID19CaseOperator extends Model
{
    protected $table = 'covid19_case_operators';

    public function case()
    {
        return $this->belongsTo(COVID19Case::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function createCaseOperator($case_id, $user_id)
    {
        if (COVID19CaseOperator::where([['case_id','=',$case_id],['user_id','=',$user_id]])->get()->count() == 0) {
            $case_operator          = new COVID19CaseOperator();
            $case_operator->case_id = $case_id;
            $case_operator->user_id = $user_id;
            $case_operator->save();
        }
    }
}
