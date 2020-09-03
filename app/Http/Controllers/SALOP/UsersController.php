<?php
declare(strict_types=1);

namespace App\Http\Controllers\SALOP;

use App\Http\Controllers\Controller;
use App\Http\Requests\SALOP\EditExtensionsRequest;
use App\Http\Requests\SALOP\EditPermissionsRequest;
use App\Models\Auth\Permission;
use App\Models\Auth\User;
use App\Models\SALOP\Extension;
use App\Models\SALOP\UserExtension;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::all();
        $extensions = Extension::all();
        return view('salop.users', ['users' => $users, 'extensions' => $extensions]);
    }

    public function editPermissions(EditPermissionsRequest $request) 
    {
        $validated = $request->validated();
        if(in_array('none',$validated["permissions"])) {
            $validated["permissions"] = [];
        }
        $user = User::find($validated["user"]);
        $permissions = $user->permissions;
        foreach($permissions as $permission) {
            if(!in_array($permission->permission,$validated["permissions"])) {
                $permission->delete();
            }
            else {
                unset($validated["permissions"][array_search($permission->permission,$validated["permissions"])]);
            }
        }
        foreach ($validated["permissions"] as $permission) {
            Permission::create($user->id,$permission);
        }
        return redirect()->route('salop.users.index');
    }

    public function editExtensions(EditExtensionsRequest $request)
    {
        $validated = $request->validated();
        if(in_array('none',$validated["extensions"])) {
            $validated["extensions"] = [];
        }
        $user = User::find($validated["user"]);
        $extensions = $user->extensions;
        foreach($extensions as $extension) {
            if(!in_array($extension->extension_id,$validated["extensions"])) {
                $extension->delete();
            }
            else {
                unset($validated["extensions"][array_search($extension->extension_id,$validated["extensions"])]);
            }
        }
        foreach ($validated["extensions"] as $extension) {
            UserExtension::create($user->id,$extension);
        }
        return redirect()->route('salop.users.index');
    }
}
