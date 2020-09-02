<?php
declare(strict_types=1);

namespace App\Http\Controllers\SALOP;

use App\Http\Controllers\Controller;

class SALOPController extends Controller
{
    public function fop2()
    {
        return view('salop.fop2');
    }

    public function missed_calls()
    {
        return view('salop.missed_calls');
    }

    public function callbacks()
    {
        return view('salop.callbacks');
    }

    public function door_opener()
    {
        return view('salop.door_opener');
    }
}
