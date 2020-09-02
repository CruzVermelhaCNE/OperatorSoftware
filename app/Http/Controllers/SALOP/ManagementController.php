<?php
declare(strict_types=1);

namespace App\Http\Controllers\SALOP;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use Auth;

class ManagementController extends Controller
{
    public function users()
    {
        if (Auth::user()->permissions->contains('permission', 1)) {
            $users = User::all();
            return view('salop.users', ['users' => $users]);
        }
        return redirect()->route('salop.fop2');
    }

    public function reports()
    {
        if (Auth::user()->permissions->contains('permission', 1)) {
            return view('salop.reports');
        }
        return redirect()->route('salop.fop2');
    }
}
