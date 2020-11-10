<?php
declare(strict_types=1);

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('isAdmin', function ($user) {
            return $user->permissions->contains('permission', 1);
        });

        Gate::define('isManager', function ($user) {
            return $user->permissions->contains('permission', 2);
        });

        Gate::define('accessGOI', function ($user) {
            return $user->permissions->contains('permission', 4);
        });

        Gate::define('accessSALOP', function ($user) {
            return $user->permissions->contains('permission', 5);
        });

        Gate::define('accessCOVID19', function ($user) {
            return $user->permissions->contains('permission', 6) || $user->permissions->contains('permission', 7) || $user->permissions->contains('permission', 8);
        });

        Gate::define('accessCOVID19Callbacks', function ($user) {
            return $user->permissions->contains('permission', 6);
        });

        Gate::define('accessCOVID19CallFlow', function ($user) {
            return $user->permissions->contains('permission', 7) || $user->permissions->contains('permission', 8);
        });
    }
}
