<?php
declare(strict_types=1);

namespace App\Http\Controllers\COVID19;

use App\Http\Controllers\Controller;

class SALOPController extends Controller
{
    public function vue()
    {
        return view('covid19.vue');
    }
}
