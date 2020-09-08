<?php
declare(strict_types=1);

namespace App\Http\Controllers\Auth\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\EditPermissionsRequest;
use App\Models\Auth\Permission;
use App\Models\Auth\User;

class UsersController extends Controller
{
    public function all()
    {
        $users = User::get(['id','name','email']);
        return $users;
    }

    public function editPermissions($id, EditPermissionsRequest $request)
    {
        $validated = $request->validated();
        if (\in_array('none', $validated['permissions'], true)) {
            $validated['permissions'] = [];
        }
        $user        = User::findOrFail($id);
        $permissions = $user->permissions;
        foreach ($permissions as $permission) {
            if (! \in_array($permission->permission, $validated['permissions'], true)) {
                $permission->delete();
            } else {
                unset($validated['permissions'][\array_search($permission->permission, $validated['permissions'], true)]);
            }
        }
        foreach ($validated['permissions'] as $permission) {
            Permission::create($user->id, $permission);
        }
        return response('');
    }
}
