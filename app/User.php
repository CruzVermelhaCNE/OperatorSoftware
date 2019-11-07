<?php
declare(strict_types=1);

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function permissions()
    {
        return $this->hasMany('App\Permission');
    }

    public function getRanksAttribute($value) {
        $ranks = "";
        $permissions = $this->permissions->toArray();
        foreach ($permissions as $key => $permission) {
            if($permission["permission"] == 1) {
                $ranks .= "Gestor";
            }
            elseif ($permission["permission"] == 2) {
                $ranks .= "Administrador";
            }
            $ranks .= ", ";
        }
        $ranks = substr_replace($ranks ,"", -2);
        if($ranks == "") {
            return "Sem Cargos";
        }
        return $ranks;
    }

    public function extensions()
    {
        return $this->hasMany('App\UserExtension');
    }

    public function getAllExtensionsAttribute($value) {
        $all_extensions = "";
        $extensions = $this->extensions->toArray();
        foreach ($extensions as $key => $extension) {
            $extension = Extension::where('id','=',$extension["id"])->first();
            $all_extensions .= $extension->number.", ";
        }
        $all_extensions = substr_replace($all_extensions ,"", -2);
        if($all_extensions == "") {
            return "Sem Extensões";
        }
        return $all_extensions;
    }
}
