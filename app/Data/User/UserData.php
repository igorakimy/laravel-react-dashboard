<?php

namespace App\Data\User;

use App\Data\Phone\PhoneData;
use App\Data\Role\RoleData;
use App\Enums\UserStatus;
use App\Models\User;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

class UserData extends Data
{
    public function __construct(
        public int $id,
        public string $first_name,
        public string $last_name,
        public string $full_name,
        public string $email,
        public UserStatus $status,

        #[DataCollectionOf(RoleData::class)]
        public DataCollection $roles,

        public array $phones,

        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public ?Carbon $created_at,

        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public ?Carbon $updated_at,
    ) {}

    public static function fromModel(User $user): self
    {
        return new self(
            id: $user->id,
            first_name: $user->first_name,
            last_name: $user->last_name,
            full_name: $user->full_name->toString(),
            email: $user->email,
            status: $user->status,
            roles: RoleData::collection($user->roles),
            phones: PhoneData::collection($user->phones)
                             ->toCollection()
                             ->pluck('number')
                             ->toArray(),
            created_at: $user->created_at,
            updated_at: $user->updated_at,
        );
    }
}
