<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Extension;
use Auth;

class AdministrationController extends Controller
{
    public function extensions()
    {
        if(Auth::user()->permissions->contains('permission', 2))  {
            $extensions = Extension::all();
            return view('extensions', ["extensions" => $extensions]);
        }
        return redirect('/panel');        
    }

}
