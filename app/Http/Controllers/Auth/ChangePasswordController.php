<?php
declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $this->validate($request, [
            'current_password' => 'required',
            'new_password'     => 'required|min:4',
        ]);

        if (Hash::check($request->current_password, $user->password)) {
            $user->password = Hash::make($request->new_password);
            $user->save();
            return redirect('/');
        }
    }
}
