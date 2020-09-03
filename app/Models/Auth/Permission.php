<?php
declare(strict_types=1);

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'user_id', 'permission',
    ];

    static public function create($user_id,$permissison_number) {
        $permission = new Permission();
        $permission->user_id = $user_id;
        $permission->permission = $permissison_number;
        $permission->save();
        return $permission;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
