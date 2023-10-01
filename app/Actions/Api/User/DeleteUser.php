<?php

namespace App\Actions\Api\User;

use App\Data\User\UserData;
use App\Models\User;
use Spatie\LaravelData\Data;

final class DeleteUser extends Data
{
    public function handle(User|int $user): UserData
    {
        if ( ! $user instanceof User) {
            $user = User::query()->findOrFail($user);
        }

        $user?->delete();

        return UserData::from($user);
    }
}
