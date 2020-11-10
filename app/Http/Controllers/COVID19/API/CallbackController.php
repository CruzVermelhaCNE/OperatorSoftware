<?php
declare(strict_types=1);

namespace App\Http\Controllers\COVID19\API;

use App\Http\Controllers\Controller;
use App\Models\COVID19\Callback;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CallbackController extends Controller
{
    public function callbacks()
    {
        return DB::table('covid19_callbacks')
            ->selectRaw('number, MAX(id) as id,MAX(date) as date')
            ->where('called_back', '=', false)
            ->groupBy('number')
            ->paginate();
    }

    public function called_back(Callback $callback)
    {
        $user_id = Auth::user()->id;
        $entries = Callback::where([['number','=',$callback->number],['date','<=',$callback->date]])->get();
        foreach ($entries as $entry) {
            $entry->markAsCalledBack($user_id);
        }
        return response(null, 200);
    }
}
