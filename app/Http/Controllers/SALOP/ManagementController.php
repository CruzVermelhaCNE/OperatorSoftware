<?php
declare(strict_types=1);

namespace App\Http\Controllers\SALOP;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;

class ManagementController extends Controller
{
    public function users()
    {
        if (Auth::user()->permissions->contains('permission', 1)) {
            $users = User::all();
            return view('users', ['users' => $users]);
        }
        return redirect('/panel');
    }

    public function reports()
    {
        if (Auth::user()->permissions->contains('permission', 1)) {
            return view('reports');
        }
        return redirect('/panel');
    }
}
