<?php

namespace App\Actions\Api\User;

use App\Actions\Api\ApiAction;
use App\Data\User\UserData;
use App\Data\User\UserUpdateData;
use App\Models\User;

final class UpdateUser extends ApiAction
{
    public function handle(User $user, UserUpdateData $data): UserData
    {
        $user->fill(
            $data
                ->except('roles')
                ->exceptWhen('password', fn(UserUpdateData $data) => $data->password == null)
                ->toArray()
        );
        $user->save();

        $user->syncRoles(
            collect($data->roles->toArray())->pluck('id')
        );

        return UserData::from($user->load(['roles', 'roles.permissions']));
    }
}
