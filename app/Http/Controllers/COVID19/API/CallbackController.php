<?php
declare(strict_types=1);

namespace App\Http\Controllers\COVID19\API;

use App\Http\Controllers\Controller;
use App\Models\COVID19\CEL;

class CallbackController extends Controller
{
    public function callbacks()
    {
        return CEL::where([['context','=','custom-app-callback-1415'],['eventtype','=','HANGUP']])->doesntHave('callback')->get();
    }
}
