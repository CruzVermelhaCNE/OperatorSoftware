<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\COVID19Case;
use Illuminate\Support\Facades\Auth;

class COVID19Controller extends Controller
{
    public function panel()
    {
        if (Auth::user()->permissions->contains('permission', 3)) {
            $cases = COVID19Case::all();
            return view('covid19.panel', ["cases" => $cases]);
        }
        return redirect('/panel');
    }
}
