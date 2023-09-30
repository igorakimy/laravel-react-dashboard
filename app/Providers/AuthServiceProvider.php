<?php

namespace App\Providers;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->grantAllPermissionsToSuperAdmin();
    }

    /**
     * Implicitly grant "Super Admin" role all permissions.
     * This works in the app by using gate-related functions
     * like auth()->user->can() and @can()
     *
     * @return void
     */
    private function grantAllPermissionsToSuperAdmin(): void
    {
        Gate::before(function(User $user, $ability) {
            return $user->hasRole(Role::SUPER_ADMIN) ? true : null;
        });
    }
}
