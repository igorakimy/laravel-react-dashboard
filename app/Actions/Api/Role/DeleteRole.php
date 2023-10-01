<?php

namespace App\Actions\Api\Role;

use App\Data\Role\RoleData;
use App\Models\Role;
use Spatie\LaravelData\Data;

final class DeleteRole extends Data
{
    public function handle(Role|int $role): RoleData
    {
        if ( ! $role instanceof Role) {
            $role = Role::query()->findOrFail($role);
        }

        $role?->delete();

        return RoleData::from($role);
    }
}
