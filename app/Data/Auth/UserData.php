<?php

namespace App\Data\Auth;

use App\Models\User;
use Spatie\LaravelData\Data;

final class UserData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public array $permissions,
        public bool $isSuperAdmin,
    ) {}

    public static function fromModel(User $user): self
    {
        return new self(
            $user->id,
            $user->name,
            $user->email,
            $user->getPermissionsViaRoles()->pluck('name')->toArray(),
            $user->isSuperAdmin(),
        );
    }
}
