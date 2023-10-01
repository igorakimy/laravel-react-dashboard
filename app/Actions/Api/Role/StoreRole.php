<?php

namespace App\Actions\Api\Role;

use App\Actions\Api\ApiAction;
use App\Data\Role\RoleData;
use App\Data\Role\RoleStoreData;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Throwable;

final class StoreRole extends ApiAction
{
    /**
     * @throws Throwable
     */
    public function handle(RoleStoreData $data): RoleData
    {
        $role = new Role();

        $role->fill($data->except('permissions')->additional([
            'guard_name' => 'web'
        ])->toArray());

        DB::transaction(function () use ($role, $data) {
            $role->save();

            $role->givePermissionTo(
                ...collect($data->permissions->toArray())->pluck('name')
            );
        });

        return RoleData::from($role);
    }
}
