<?php
declare(strict_types=1);

namespace App\Models\SALOP;

use Illuminate\Database\Eloquent\Model;

class CDR extends Model
{
    protected $connection = 'cdr_mysql';

    protected $table = 'cdr';
}
