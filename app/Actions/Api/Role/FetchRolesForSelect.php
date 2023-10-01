<?php

namespace App\Actions\Api\Role;

use App\Actions\Api\ApiAction;
use App\Data\Role\RoleData;
use App\Models\Role;
use Spatie\LaravelData\DataCollection;

final class FetchRolesForSelect extends ApiAction
{
    public function handle(): DataCollection
    {
        $roles = Role::query()->with('permissions')->get();

        return RoleData::collection($roles);
    }
}
