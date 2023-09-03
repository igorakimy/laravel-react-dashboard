<?php

namespace App\Http\Controllers\Api;

use App\Data\User\UserData;
use App\Data\User\UserStoreData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\StoreUserRequest;
use App\Http\Requests\Api\User\UpdateUserRequest;
use App\Models\User;
use Spatie\LaravelData\Exceptions\InvalidDataClass;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::query()
                     ->orderBy('id', 'desc')
                     ->paginate(10);

        return UserData::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = UserStoreData::fromRequest($request)->toArray();
        $user = User::query()->create($data);
        return UserData::from($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
