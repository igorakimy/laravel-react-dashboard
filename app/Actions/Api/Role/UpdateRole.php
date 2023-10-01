<?php

namespace App\Actions\Api\Role;

use App\Actions\Api\ApiAction;
use App\Data\Role\RoleData;
use App\Data\Role\RoleUpdateData;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Throwable;

final class UpdateRole extends ApiAction
{
    /**
     * @throws Throwable
     */
    public function handle(Role $role, RoleUpdateData $data): RoleData
    {
        $role->fill($data->except('permissions')->toArray());

        DB::transaction(function () use ($role, $data) {
            $role->save();

            $role->syncPermissions(
                ...collect($data->permissions->toArray())->pluck('name')
            );
        });

        return RoleData::from($role->load('permissions'));
    }
}
