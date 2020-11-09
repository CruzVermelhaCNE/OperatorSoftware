<?php
declare(strict_types=1);

namespace App\Models\COVID19;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class Callbacks extends Model
{
    public function create($cdr_system_id,$called_back_user_id) 
    {
        $callback = new self();
        $callback->cdr_system_id = $cdr_system_id;
        $callback->called_back_user_id = $called_back_user_id;
        $callback->save();
        return $callback;
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'called_back_user_id');
    }
}
