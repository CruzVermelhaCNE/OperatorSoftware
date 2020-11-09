<?php
declare(strict_types=1);

namespace App\Models\Auth;

use App\Models\SALOP\Extension;
use App\Models\SALOP\UserExtension;
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
        'name', 'email', 'password', 'microsoft_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','permissions','extensions',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['permission_names','extension_numbers'];

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function getPermissionNamesAttribute($value)
    {
        $ranks       = '';
        $permissions = $this->permissions->toArray();
        \sort($permissions);
        foreach ($permissions as $permission) {
            if ($permission['permission'] == 1) {
                $ranks .= 'Administrador';
            } elseif ($permission['permission'] == 2) {
                $ranks .= 'Gestor';
            } elseif ($permission['permission'] == 3) {
                // Rank used for removed module
                continue;
            } elseif ($permission['permission'] == 4) {
                $ranks .= 'Gestão Operacional Integrada';
            } elseif ($permission['permission'] == 5) {
                $ranks .= 'SALOP';
            } elseif ($permission['permission'] == 6) {
                $ranks .= 'COVID-19 Callbacks';
            } elseif ($permission['permission'] == 7) {
                $ranks .= 'COVID-19 Patient Information';
            } elseif ($permission['permission'] == 8) {
                $ranks .= 'COVID-19 Results';
            }
            $ranks .= ', ';
        }
        $ranks = \substr_replace($ranks, '', -2);
        if ($ranks == '') {
            return 'Sem Permissões';
        }
        return $ranks;
    }

    public function getExtensionNumbersAttribute($value)
    {
        $extension_numbers = '';
        $extensions        = $this->extensions;
        foreach ($extensions as $extension_link) {
            $extension_numbers .= $extension_link->extension->number.', ';
        }
        $extension_numbers = \substr_replace($extension_numbers, '', -2);
        if ($extension_numbers == '') {
            return 'Sem Extensões';
        }
        return $extension_numbers;
    }

    public function extensions()
    {
        return $this->hasMany(UserExtension::class);
    }

    public function getAllExtensionsAttribute($value)
    {
        $all_extensions = '';
        $extensions     = $this->extensions->toArray();
        foreach ($extensions as $key => $extension) {
            $extension = Extension::where('id', '=', $extension['extension_id'])->first();
            if ($extension) {
                $all_extensions .= $extension->number.', ';
            }
        }
        $all_extensions = \substr_replace($all_extensions, '', -2);
        if ($all_extensions == '') {
            return 'Sem Extensões';
        }
        return $all_extensions;
    }
}
