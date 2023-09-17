<?php

namespace App\Models\Traits;

use Illuminate\Support\Collection;

trait HasPermissions
{
    use \Spatie\Permission\Traits\HasPermissions {
        givePermissionTo as protected spatieGivePermissionTo;
    }


    public function givePermissionTo(...$permissions): static
    {
        $permissions = $this->getFormattedPermissionsCollection(...$permissions);

        $this->spatieGivePermissionTo(...$permissions);

        return $this;
    }

    /**
     * Get the permissions of roles names.
     *
     * @param $permissions
     *
     * @return array
     */
    protected function getFormattedPermissionsCollection(...$permissions): array
    {
        return collect($permissions)->map(function ($permission) {
            if ($permission instanceof \BackedEnum) {
                return $permission->value;
            }

            return $permission;
        })->toArray();
    }
}
