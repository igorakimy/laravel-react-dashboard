<?php

namespace App\Http\Controllers\Api;

use App\Actions\Api\User\DeleteUser;
use App\Actions\Api\User\FetchPaginatedUsers;
use App\Actions\Api\User\ShowUser;
use App\Actions\Api\User\StoreUser;
use App\Actions\Api\User\UpdateUser;
use App\Data\User\UserPaginationData;
use App\Data\User\UserSortingData;
use App\Data\User\UserStoreData;
use App\Data\User\UserUpdateData;
use App\Enums\UserStatus;
use App\Http\Controllers\ApiController;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as RespCode;

class UserController extends ApiController
{
    public function __construct(
        private readonly FetchPaginatedUsers $fetchPaginatedUsersAction,
        private readonly ShowUser $showUserAction,
        private readonly StoreUser $storeUserAction,
        private readonly UpdateUser $updateUserAction,
        private readonly DeleteUser $deleteUserAction,
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $paginationData = UserPaginationData::fromRequest($request);
        $sortingData = UserSortingData::fromRequest($request);

        $users = $this->fetchPaginatedUsersAction->handle(
            $paginationData,
            $sortingData,
        );

        return response($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $data = UserStoreData::fromRequest($request);

        $userData = $this->storeUserAction->handle($data);

        return response($userData, RespCode::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $userData = $this->showUserAction->handle($user);

        return response($userData);
    }

    /**
     * Update the specified resource in storage.
     * @throws Exception
     */
    public function update(Request $request, User $user)
    {
        $data = UserUpdateData::fromRequest($request);

        $userData = $this->updateUserAction->handle($user, $data);

        return response($userData);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $userData = $this->deleteUserAction->handle($user);

        return response($userData, RespCode::HTTP_NO_CONTENT);
    }

    /**
     * Get available user statuses.
     *
     * @return Response
     */
    public function showStatuses()
    {
        return response(UserStatus::toArray());
    }
}
