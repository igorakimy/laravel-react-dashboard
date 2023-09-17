<?php

namespace App\Http\Middleware;

use App\Enums\Permission;
use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @param  null  $permission
     * @param  null  $guard
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission = null, $guard = null): mixed
    {
        $guard = $guard ?? config('auth.defaults.guard');

        $authGuard = app('auth')->guard($guard);

        if ($authGuard->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        if (! is_null($permission)) {
            $permissions = is_array($permission)
                ? $permission
                : explode('|', $permission);
        }

        if ( is_null($permission) ) {
            $permission = $request->route()->getName();

            $permissions = [$permission];
        }

        foreach ($permissions as $permission) {
            if ($authGuard->user()->can(Permission::CAN_DO_ANYTHING->value)
                || $authGuard->user()->can($permission, $guard)) {
                return $next($request);
            }
        }

        throw UnauthorizedException::forPermissions($permissions);
    }
}
