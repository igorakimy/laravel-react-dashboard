<?php

namespace App\Http\Controllers\Api;

use App\Data\Permission\PermissionData;
use App\Http\Controllers\ApiController;
use App\Models\Permission;
use Illuminate\Http\Request;

final class PermissionController extends ApiController
{
    public function index()
    {
        $permissions = Permission::query()->get();

        return PermissionData::collection($permissions);
    }

    public function show(Request $request, Permission $permission)
    {

    }

    public function store(Request $request)
    {

    }

    public function update(Request $request, Permission $permission)
    {

    }

    public function destroy(Permission $permission)
    {

    }
}
