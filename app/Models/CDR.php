<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CDR extends Model
{
    protected $connection = 'cdr_mysql';

    protected $table = 'cdr';
}
