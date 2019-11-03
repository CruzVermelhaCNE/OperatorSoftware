<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CDR extends Model
{
    protected $connection = 'cdr_mysql';
    protected $table = 'cdr';
}
