<?php

namespace App\Actions\Api\Role;

use App\Actions\Api\ApiAction;
use App\Data\Role\RoleData;
use App\Data\Role\RolePaginationData;
use App\Data\Role\RoleSortingData;
use App\Models\Permission;
use App\Models\Role;
use App\Enums\Role as RoleEnum;
use Spatie\LaravelData\PaginatedDataCollection;

final class FetchPaginatedRoles extends ApiAction
{
    public function handle(RolePaginationData $pagination, RoleSortingData $sorting): PaginatedDataCollection
    {
        $roles = Role::query()
                     ->with('permissions')
                     ->whereNot('name', RoleEnum::SUPER_ADMIN);

        $sortColumn = $sorting->column;

        if ($sorting->column == 'permissions') {
            $sortColumn = Permission::query()->selectRaw('STRING_AGG(display_name, \', \')')
                                    ->join(
                                        'role_has_permissions',
                                        'role_has_permissions.permission_id',
                                        '=', 'permissions.id'
                                    )
                                    ->whereColumn('role_has_permissions.role_id', '=', 'roles.id')
                                    ->groupBy('role_has_permissions.role_id');
        }

        $roles = $roles->orderBy($sortColumn, $sorting->direction)
                       ->paginate(
                           $pagination->pageSize,
                           $pagination->columns,
                           $pagination->pageName,
                           $pagination->currentPage
                       );

        return RoleData::collection($roles);
    }
}
