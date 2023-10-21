<?php

namespace App\Http\Controllers\Api;

use App\Actions\Api\Role\DeleteRole;
use App\Actions\Api\Role\FetchPaginatedRoles;
use App\Actions\Api\Role\FetchRolesForSelect;
use App\Actions\Api\Role\ShowRole;
use App\Actions\Api\Role\StoreRole;
use App\Actions\Api\Role\UpdateRole;
use App\Data\Role\RolePaginationData;
use App\Data\Role\RoleSortingData;
use App\Data\Role\RoleStoreData;
use App\Data\Role\RoleUpdateData;
use App\Http\Controllers\ApiController;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as RespCode;
use Throwable;

final class RoleController extends ApiController
{
    public function __construct(
        private readonly FetchPaginatedRoles $fetchPaginatedRolesAction,
        private readonly FetchRolesForSelect $fetchRolesForSelectAction,
        private readonly ShowRole $showRoleAction,
        private readonly StoreRole $storeRoleAction,
        private readonly UpdateRole $updateRoleAction,
        private readonly DeleteRole $deleteRoleAction,
    ) {
    }

    /**
     * Get list of roles.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        $roles = match ($request->input('kind')) {
            'select' => $this->fetchRolesForSelectAction->handle(),
            default => $this->fetchPaginatedRolesAction->handle(
                RolePaginationData::fromRequest($request),
                RoleSortingData::fromRequest($request),
            )
        };

        return response($roles);
    }

    /**
     * Get one role.
     *
     * @param  Role  $role
     *
     * @return Response
     */
    public function show(Role $role): Response
    {
        $role = $this->showRoleAction->handle($role);

        return response($role);
    }

    /**
     * Create a new role.
     *
     * @param  Request  $request
     *
     * @return Response
     * @throws Throwable
     */
    public function store(Request $request): Response
    {
        $data = RoleStoreData::fromRequest($request);

        $role = $this->storeRoleAction->handle($data);

        return response($role, RespCode::HTTP_CREATED);
    }

    /**
     * Update the role.
     *
     * @param  Request  $request
     * @param  Role  $role
     *
     * @return Response
     * @throws Throwable
     */
    public function update(Request $request, Role $role): Response
    {
        $data = RoleUpdateData::fromRequest($request);

        $role = $this->updateRoleAction->handle($role, $data);

        return response($role);
    }

    /**
     * Delete the role.
     *
     * @param  Role  $role
     *
     * @return Response
     */
    public function destroy(Role $role): Response
    {
        $role = $this->deleteRoleAction->handle($role);

        return response($role, RespCode::HTTP_NO_CONTENT);
    }
}
