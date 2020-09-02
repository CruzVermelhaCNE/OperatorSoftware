<?php
declare(strict_types=1);

namespace App\Http\Controllers\SALOP;

use App\Http\Controllers\Controller;
use App\Models\SALOP\Extension;
use Auth;

class AdministrationController extends Controller
{
    public function extensions()
    {
        if (Auth::user()->permissions->contains('permission', 2)) {
            $extensions = Extension::all();
            return view('salop.extensions', ['extensions' => $extensions]);
        }
        return redirect()->route('salop.fop2');
    }
}
