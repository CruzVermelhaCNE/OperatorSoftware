<?php
declare(strict_types=1);

namespace App\Http\Controllers\SALOP;

use App\Http\Controllers\Controller;

class SALOPController extends Controller
{
    public function vue()
    {
        return view('salop.vue');
    }
}
