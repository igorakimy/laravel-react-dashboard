<?php

namespace App\Actions\Api\User;

use App\Actions\Api\ApiAction;
use App\Data\User\UserData;
use App\Data\User\UserStoreData;
use App\Models\Phone;
use App\Models\User;

final class StoreUser extends ApiAction
{
    public function handle(UserStoreData $data): UserData
    {
        // create new user
        $user = User::query()->create(
            $data->except('roles', 'phones')->toArray()
        );

        // attach the roles
        $user->roles()->attach(
            collect($data->roles->toArray())->pluck('id')
        );

        // attach the phones
        $existingPhones = Phone::all()->pluck('number')->toArray();

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

        return UserData::from($user);
    }
}
