<?php

namespace App\Actions\Api\User;

use App\Actions\Api\ApiAction;
use App\Data\User\UserData;
use App\Data\User\UserUpdateData;
use App\Models\Phone;
use App\Models\User;

final class UpdateUser extends ApiAction
{
    public function handle(User $user, UserUpdateData $data): UserData
    {
        // update user
        $user->fill(
            $data
                ->except('roles', 'phones')
                ->exceptWhen('password', fn(UserUpdateData $data) => $data->password == null)
                ->toArray()
        );
        $user->save();

        // synchronize user roles
        $user->syncRoles(
            collect($data->roles->toArray())->pluck('id')
        );

        // attach phones
        $existingPhones = Phone::all()->pluck('number')->toArray();
        $diff = array_diff($user->phones?->pluck('number')->toArray() ?: [], $data->phones);
        Phone::query()->whereIn('number', $diff)->delete();

        foreach ($data->phones as $phone) {
            if (in_array($phone, $existingPhones)) {
                continue;
            }
            Phone::query()->create([
                'number' => $phone,
                'active' => true,
                'user_id' => $user->id,
            ]);
        }

        return UserData::from($user->load(['roles', 'roles.permissions']));
    }
}
