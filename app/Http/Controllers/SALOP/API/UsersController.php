<?php
declare(strict_types=1);

namespace App\Http\Controllers\SALOP\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SALOP\EditExtensionsRequest;
use App\Models\Auth\User;
use App\Models\SALOP\UserExtension;

class UsersController extends Controller
{
    public function editExtensions($id, EditExtensionsRequest $request)
    {
        $validated = $request->validated();
        if (\in_array('none', $validated['extensions'], true)) {
            $validated['extensions'] = [];
        }
        $user       = User::findOrFail($id);
        $extensions = $user->extensions;
        foreach ($extensions as $extension) {
            if (! \in_array($extension->extension_id, $validated['extensions'], true)) {
                $extension->delete();
            } else {
                unset($validated['extensions'][\array_search($extension->extension_id, $validated['extensions'], true)]);
            }
        }
        foreach ($validated['extensions'] as $extension) {
            UserExtension::create($user->id, $extension);
        }
        return response(null);
    }
}
