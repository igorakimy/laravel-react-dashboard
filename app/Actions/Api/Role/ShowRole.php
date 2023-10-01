<?php

namespace App\Actions\Api\Role;

use App\Data\Role\RoleData;
use App\Models\Role;
use Spatie\LaravelData\Data;

final class ShowRole extends Data
{
    public function handle(Role|int $role): RoleData
    {
        if ( ! $role instanceof Role) {
            $role = Role::query()
                        ->with('permissions')
                        ->findOrFail($role);
        }

        $role->load('permissions');

        return RoleData::from($role);
    }
}
