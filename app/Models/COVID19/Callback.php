<?php
declare(strict_types=1);

namespace App\Models\COVID19;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class Callback extends Model
{
    protected $table = 'covid19_callbacks';

    public static function create($cdr_system_id, $number, $date)
    {
        $callback                = new self();
        $callback->cdr_system_id = $cdr_system_id;
        $callback->number        = $number;
        $callback->date          = $date;
        $callback->save();
        return $callback;
    }

    public function markAsCalledBack($called_back_user_id)
    {
        $this->called_back         = true;
        $this->called_back_user_id = $called_back_user_id;
        $this->save();
        return $this;
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'called_back_user_id');
    }
}
