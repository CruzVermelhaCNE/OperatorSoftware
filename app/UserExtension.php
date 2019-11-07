<?php
declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;


class UserExtension extends Model
{
    protected $fillable = [
        'user_id','extension_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function extension()
    {
        return $this->belongsTo('App\Extension');
    }
}
