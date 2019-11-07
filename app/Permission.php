<?php
declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;


class Permission extends Model
{
    protected $fillable = [
        'user_id', 'permission'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
