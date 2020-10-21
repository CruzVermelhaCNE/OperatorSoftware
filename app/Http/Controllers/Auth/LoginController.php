<?php
declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('microsoft')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $auth_user = Socialite::driver('microsoft')->user();
        $user      = null;
        if (env('MICROSOFT_ALLOW_CREATION') == true) {
            $user = User::updateOrCreate(
                [
                    'email' => $auth_user->email,
                ],
                [
                    'microsoft_token' => $auth_user->token,
                    'name'            => $auth_user->name,
                    'password'        => '$2y$12$TXBCpDlEYzv5qBP4Dsu50OkzvAn.GCM6JXGvL9Vv/EPlGgvb7Y5wG',
                ]
            );
        } else {
            $user                  = User::where('email', '=', $auth_user->email)->firstOrFail();
            $user->microsoft_token = $auth_user->token;
            $user->name            = $auth_user->name;
            $user->save();
        }
        Auth::login($user, true);
        return redirect()->route('auth.index'); // Redirect to a secure page
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function index()
    {
        return redirect()->route('salop.index');
    }

    public function user()
    {
        return response()->json(Auth::user());
    }
}
