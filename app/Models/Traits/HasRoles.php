<?php

namespace App\Models\Traits;

use App\Models\Permission;
use App\Models\Role;
use BackedEnum;
use App\Enums\Role as RoleEnum;
use App\Enums\Permission as PermissionEnum;
use Illuminate\Support\Collection;
use Spatie\Permission\Traits\HasRoles as SpatieHasRoles;

trait HasRoles
{
    use SpatieHasRoles {
        hasRole as protected spatieHasRole;
        assignRole as protected spatieAssignRole;
        syncRoles as protected spatieSyncRoles;
        hasPermissionTo as protected spatieHasPermissionTo;
        hasPermissionViaRole as protected spatieHasPermissionViaRole;
    }

    /**
     * Check if the user has any of the given roles
     *
     * @param array|Role|RoleEnum|Collection $roles
     * @param  string|null  $guard
     *
     * @return bool
     */
    public function hasRole($roles, string $guard = null): bool
    {
        if (is_array($roles) || $roles instanceof Collection) {
            $roles = $this->getFormattedRoles(...$roles);
        } else {
            $roles = $this->getFormattedRole($roles);
        }

        return $this->spatieHasRole($roles, $guard);
    }

    /**
     * Assign multiple roles to the user
     *
     * @param RoleEnum|Role|string $roles
     *
     * @return $this
     */
    public function assignRole(...$roles): static
    {
        $roles = $this->getFormattedRoles(...$roles);

        $this->spatieAssignRole(...$roles);

        return $this;
    }

    /**
     * Detach all roles and attach new.
     *
     * @param RoleEnum|Role|string $roles
     *
     * @return static
     */
    public function syncRoles(...$roles): static
    {
        $this->roles()->detach();

        return $this->assignRole(...$roles);
    }

    /**
     * Check if role has permission;
     *
     * @param PermissionEnum|Permission|string|int $permission
     * @param $guardName
     *
     * @return bool
     */
    public function hasPermissionTo($permission, $guardName = null): bool
    {
        $permissionClass = $this->getPermissionClass();

        if ($permission instanceof BackedEnum) {
            $permission = $permissionClass->findByName(
                $permission->value, $guardName ?? $this->getDefaultGuardName()
            );
        }

        if (is_string($permission)) {
            $permission = $permissionClass->findByName(
                $permission, $guardName ?? $this->getDefaultGuardName()
            );
        }

        if (is_int($permission)) {
            $permission = $permissionClass->findById(
                $permission, $guardName ?? $this->getDefaultGuardName()
            );
        }

        return $this->hasDirectPermission($permission)
               || $this->hasPermissionViaRole($permission);
    }


    /**
     * Check has permission via role.
     *
     * @param  Permission  $permission
     *
     * @return bool
     */
    protected function hasPermissionViaRole(Permission $permission): bool
    {
        return $this->hasRole($permission->roles);
    }

    /**
     * Get the collection of roles names.
     *
     * @param ...$roles
     *
     * @return array
     */
    protected function getFormattedRoles(...$roles): array
    {
        return collect($roles)->map(function ($role) {
            return $this->getFormattedRole($role);
        })->toArray();
    }

    /**
     * Get formatted role to string.
     *
     * @param $role
     *
     * @return string
     */
    protected function getFormattedRole($role): mixed
    {
        if ($role instanceof BackedEnum) {
            return $role->value;
        }

        if ($role instanceof Role) {
            return $role->name;
        }

        return $role;
    }
}
