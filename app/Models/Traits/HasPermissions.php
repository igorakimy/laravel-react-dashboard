<?php

namespace App\Models\Traits;

use App\Models\Permission;
use BackedEnum;
use App\Enums\Permission as PermissionEnum;
use Closure;
use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use Spatie\Permission\Traits\HasPermissions as SpatieHasPermissions;

trait HasPermissions
{
    use SpatieHasPermissions {
        hasPermissionTo as protected spatieHasPermissionTo;
        givePermissionTo as protected spatieGivePermissionTo;
        syncPermissions as protected spatieSyncPermissions;
        getDefaultGuardName as protected spatieGetDefaultGuardName;
    }

    /**
     * Check if role has permission.
     *
     * @param int|string|Permission|PermissionEnum $permission
     * @param string|null $guardName
     *
     * @return bool
     */
    public function hasPermissionTo($permission, $guardName = null): bool
    {
        $permission = $this->getFormattedPermission($permission);

        return $this->spatieHasPermissionTo($permission, $guardName);
    }

    /**
     * Give permissions to role.
     *
     * @param string|Permission|PermissionEnum $permissions
     *
     * @return $this
     */
    public function givePermissionTo(...$permissions): static
    {
        $permissions = $this->getFormattedPermissions(...$permissions);

        $this->spatieGivePermissionTo(...$permissions);

        return $this;
    }

    /**
     * Detach all exist role permissions and attach new.
     *
     * @param int|string|Permission|PermissionEnum $permissions
     *
     * @return $this
     */
    public function syncPermissions(...$permissions): static
    {
        $this->permissions()->detach();

        return $this->givePermissionTo(...$permissions);
    }

    /**
     * Get default guard name.
     *
     * @return Closure|Repository|Application|mixed
     */
    public function getDefaultGuardName(): mixed
    {
        $default = config('auth.defaults.guard');

        return $this->getGuardNames()->first() ?: $default;
    }

    /**
     * Get formatted permissions for enums supporting.
     *
     * @param int|string|Permission|PermissionEnum $permissions
     *
     * @return array
     */
    private function getFormattedPermissions(...$permissions): array
    {
        return collect($permissions)->map(function ($permission) {
            return $this->getFormattedPermission($permission);
        })->toArray();
    }

    /**
     * Get formatted permission.
     *
     * @param $permission
     *
     * @return int|string
     */
    private function getFormattedPermission($permission): int|string
    {
        if ($permission instanceof BackedEnum) {
            return $permission->value;
        }

        if ($permission instanceof Permission) {
            return $permission->name;
        }

        return $permission;
    }
}
