<?php
declare(strict_types=1);

namespace App\Http\Controllers\SALOP;

use App\Http\Controllers\Controller;
use App\Models\SALOP\CDR;
use Illuminate\Support\Carbon;

class CDRMissedCallsController extends Controller
{
    public function fetch()
    {
        $dids = [
            '300501900',
            '300501960',
            '239825395',
            '239006911',
        ];
        $unanswered_array = CDR::where('dcontext', '=', 'ext-queues')->where('duration', '>', 10)->whereIn('did', $dids)->whereNotIn('src', $dids)->where('disposition', '=', 'NO ANSWER')->whereDate('calldate', '>=', Carbon::now()->subDays(7))->orderBy('calldate', 'DESC')->get(['calldate','src','clid','duration','did'])->toArray();
        $array            = ['data' => []];
        foreach ($unanswered_array as $unanswered) {
            if (CDR::where('dst', 'LIKE', '%'.$unanswered['src'].'%')->where('calldate', '>', $unanswered['calldate'])->count() == 0) {
                if (CDR::where('src', 'LIKE', '%'.$unanswered['src'].'%')->where('disposition', '=', 'ANSWERED')->where('calldate', '>', $unanswered['calldate'])->count() == 0) {
                    \array_push($array['data'], $unanswered);
                }
            }
        }
        return response()->json($array);
    }
}
