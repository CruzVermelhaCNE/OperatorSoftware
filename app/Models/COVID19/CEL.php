<?php
declare(strict_types=1);

namespace App\Models\COVID19;

use Illuminate\Database\Eloquent\Model;

class CEL extends Model
{
    protected $connection = 'cdr_mysql';

    protected $table = 'cel';

    protected $primaryKey = 'id';

    protected $dates = [
        'eventtime',
    ];

    public function callback()
    {
        return $this->hasOne(Callback::class, 'cdr_system_id', 'uniqueid');
    }
}
