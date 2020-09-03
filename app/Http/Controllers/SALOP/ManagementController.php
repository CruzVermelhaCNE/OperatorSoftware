<?php
declare(strict_types=1);

namespace App\Http\Controllers\SALOP;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;

class ManagementController extends Controller
{
    public function reports()
    {
        return view('salop.reports');
    }
}
