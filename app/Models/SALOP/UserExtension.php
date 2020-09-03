<?php
declare(strict_types=1);

namespace App\Models\SALOP;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class UserExtension extends Model
{
    protected $fillable = [
        'user_id','extension_id',
    ];

    static public function create($user_id,$extension_id) {
        $user_extension = new UserExtension();
        $user_extension->user_id = $user_id;
        $user_extension->extension_id = $extension_id;
        $user_extension->save();
        return $user_extension;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function extension()
    {
        return $this->belongsTo(Extension::class);
    }
}
