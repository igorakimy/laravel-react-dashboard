<?php

namespace App\Models\Traits;

use Illuminate\Support\Collection;

trait HasRoles
{
    use \Spatie\Permission\Traits\HasRoles {
        hasRole as protected spatieHasRole;
        assignRole as protected spatieAssignRole;
    }

    /**
     * Assign multiple roles to the user
     *
     * @param ...$roles
     *
     * @return $this
     */
    public function assignRole(...$roles): static
    {
        $roles = $this->getFormattedRolesCollection(...$roles);

        $this->spatieAssignRole(...$roles);

        return $this;
    }

    /**
     * Check if the user has any of the given roles
     *
     * @param array|Collection $roles
     * @param  string|null  $guard
     *
     * @return bool
     */
    public function hasRole($roles, string $guard = null): bool
    {
        if (is_array($roles)) {
            $roles = $this->getFormattedRolesCollection(...$roles);
        }

        return $this->spatieHasRole($roles, $guard);
    }

    /**
     * Get the collection of roles names.
     *
     * @param ...$roles
     *
     * @return array
     */
    protected function getFormattedRolesCollection(...$roles): array
    {
        return collect($roles)->map(function ($role) {
            if ($role instanceof \BackedEnum) {
                return $role->value;
            }

            return $role;
        })->toArray();
    }
}
