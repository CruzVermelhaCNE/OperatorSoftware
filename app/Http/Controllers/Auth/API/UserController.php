<?php
declare(strict_types=1);

namespace App\Http\Controllers\Auth\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function info()
    {
        $user = Auth::user();
        return $user;
    }

    public function permissions()
    {
        $permissions = Auth::user()->permissions->pluck('permission')->toArray();
        return $permissions;
    }

    public function extensions()
    {
        $extensions_links = Auth::user()->extensions;
        $extensions       = [];
        foreach ($extensions_links as $key => $link) {
            $extensions[] = ['number' => $link->extension->number, 'password' => $link->extension->password];
        }
        return $extensions;
    }

    public function permissionsAccessSALOP()
    {
        $gate = Gate::inspect('accessSALOP');
        return $gate->allowed();
    }

    public function permissionsAccessGOI()
    {
        $gate = Gate::inspect('accessGOI');
        return $gate->allowed();
    }

    public function permissionsIsManager()
    {
        $gate = Gate::inspect('isManager');
        return $gate->allowed();
    }

    public function permissionsIsAdmin()
    {
        $gate = Gate::inspect('isAdmin');
        return $gate->allowed();
    }
}
