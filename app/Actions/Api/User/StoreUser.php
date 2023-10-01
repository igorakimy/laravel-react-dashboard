<?php

namespace App\Actions\Api\User;

use App\Actions\Api\ApiAction;
use App\Data\User\UserData;
use App\Data\User\UserStoreData;
use App\Models\User;

final class StoreUser extends ApiAction
{
    public function handle(UserStoreData $data): UserData
    {
        $user = User::query()->create(
            $data->except('roles')->toArray()
        );

        $user->roles()->attach(
            collect($data->roles->toArray())->pluck('id')
        );

        return UserData::from($user);
    }
}
