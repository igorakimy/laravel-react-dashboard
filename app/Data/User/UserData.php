<?php

namespace App\Data\User;

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

        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public ?Carbon $created_at,

        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public ?Carbon $updated_at,
    ) {}

    public static function fromModel(User $user): self
    {
        return new self(
            $user->id,
            $user->first_name,
            $user->last_name,
            $user->full_name->toString(),
            $user->email,
            $user->status,
            RoleData::collection($user->roles),
            $user->created_at,
            $user->updated_at,
        );
    }
}
