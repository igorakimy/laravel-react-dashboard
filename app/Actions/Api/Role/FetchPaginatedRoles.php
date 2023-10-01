<?php

namespace App\Actions\Api\Role;

use App\Actions\Api\ApiAction;
use App\Data\Role\RoleData;
use App\Data\Role\RolePaginationData;
use App\Models\Permission;
use App\Models\Role;
use App\Enums\Role as RoleEnum;
use Spatie\LaravelData\PaginatedDataCollection;

final class FetchPaginatedRoles extends ApiAction
{
    public function handle(RolePaginationData $data): PaginatedDataCollection
    {
        $roles = Role::query()
                     ->with('permissions')
                     ->whereNot('name', RoleEnum::SUPER_ADMIN);

        $sortColumn = $data->sortColumn;

        if ($data->sortColumn == 'permissions') {
            $sortColumn = Permission::query()->selectRaw('STRING_AGG(display_name, \', \')')
                                    ->join(
                                        'role_has_permissions',
                                        'role_has_permissions.permission_id',
                                        '=', 'permissions.id'
                                    )
                                    ->whereColumn('role_has_permissions.role_id', '=', 'roles.id')
                                    ->groupBy('role_has_permissions.role_id');
        }

        $roles = $roles->orderBy($sortColumn, $data->sortDirection)
                       ->paginate(
                           $data->pageSize,
                           ['*'],
                           'page',
                           $data->currentPage
                       );

        return RoleData::collection($roles);
    }
}
