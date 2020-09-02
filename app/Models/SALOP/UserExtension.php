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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function extension()
    {
        return $this->belongsTo(Extension::class);
    }
}
