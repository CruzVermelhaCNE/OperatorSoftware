<?php

namespace App\Http\Controllers;

use App\CDR;
use Illuminate\Support\Carbon;

class CDRBusyCallsController extends Controller
{
    public function fetch()
    {
        $dids = [
            "300501900",
            "300501960",
            "239825395",
            "239006911",
        ];
        $unanswered_array = CDR::where('lastapp', '=', 'busy')->whereIn('did', $dids)->whereNotIn('src', $dids)->whereDate('calldate', '>=', Carbon::now())->orderBy('calldate', 'DESC')->get(['calldate','src','clid','duration','did'])->toArray();
        $array = ["data" => []];
        foreach ($unanswered_array as $unanswered) {
            if (CDR::where('dst','LIKE', '%'.$unanswered["src"].'%')->where('calldate', '>', $unanswered['calldate'])->count() == 0) {
                if (CDR::where('dcontext', '=', 'ext-queues')->where('src','LIKE', '%'.$unanswered["src"])->where('disposition', '=', 'ANSWERED')->where('calldate', '>', $unanswered['calldate'])->count() == 0) {
                    array_push($array["data"], $unanswered);
                }
            }
        }
        return response()->json($array);
    }
}
