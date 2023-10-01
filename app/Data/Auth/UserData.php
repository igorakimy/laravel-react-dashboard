<?php

namespace App\Data\Auth;

use App\Data\Role\RoleData;
use App\Models\User;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

final class UserData extends Data
{
    public function __construct(
        public int $id,
        public string $first_name,
        public string $last_name,
        public string $email,
        public array $permissions,

        #[DataCollectionOf(RoleData::class)]
        public DataCollection $roles,

        public bool $isSuperAdmin,
    ) {}

    public static function fromModel(User $user): self
    {
        return new self(
            $user->id,
            $user->first_name,
            $user->last_name,
            $user->email,
            $user->getPermissionsViaRoles()->pluck('name')->toArray(),
            RoleData::collection($user->roles),
            $user->isSuperAdmin(),
        );
    }
}
