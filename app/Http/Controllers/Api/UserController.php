<?php

namespace App\Http\Controllers\Api;

use App\Data\User\UserData;
use App\Data\User\UserStoreData;
use App\Data\User\UserUpdateData;
use App\Enums\Permission;
use App\Http\Requests\Api\User\IndexUserRequest;
use App\Http\Requests\Api\User\StoreUserRequest;
use App\Http\Requests\Api\User\UpdateUserRequest;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class UserController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexUserRequest $request)
    {
        $page = $request->validated('pagination.current');
        $perPage = $request->validated('pagination.pageSize');
        $sortField = $request->validated('field', 'id');
        $sortDirection = $request->validated('order', 'ascend') == 'ascend' ? 'asc' : 'desc';

        $users = User::query()
                     ->orderBy($sortField, $sortDirection)
                     ->paginate($perPage, ['*'], 'page', $page);

        return UserData::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = UserStoreData::fromRequest($request)->toArray();
        $user = User::query()->create($data);
        return response(UserData::from($user), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return UserData::from($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = UserUpdateData::fromRequest($request)
            ->exceptWhen('password', fn(UserUpdateData $data) => $data->password == null)
            ->toArray();

        $user->update($data);
        return UserData::from($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response(UserData::from($user), Response::HTTP_NO_CONTENT);
    }
}
