<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;

class ManagementController extends Controller
{
    public function users()
    {
        if(Auth::user()->permissions->contains('permission', 1))  {
            $users = User::all();
            return view('users', ["users"=> $users]);
        }
        return redirect('/panel');        
    }

    public function reports()
    {
        if(Auth::user()->permissions->contains('permission', 1))  {
            return view('reports');
        }
        return redirect('/panel');   
    }
}
