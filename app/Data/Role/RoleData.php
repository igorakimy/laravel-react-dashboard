<?php

namespace App\Data\Role;

use App\Data\Permission\PermissionData;
use App\Models\Role;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

final class RoleData extends Data
{
    public function __construct(
        public int|null $id,
        public string $name,

        #[DataCollectionOf(PermissionData::class)]
        public Lazy|DataCollection $permissions,

        #[WithCast(DateTimeInterfaceCast::class, format: "Y-m-d\TH:i:s.u\Z")]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public Carbon|null $created_at,

        #[WithCast(DateTimeInterfaceCast::class, format: "Y-m-d\TH:i:s.u\Z")]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public Carbon|null $updated_at,
    ) {
    }

    public static function fromModel(Role $role): self
    {
        return new self(
            id: $role->id,
            name: $role->name,
            permissions: Lazy::create(fn() => PermissionData::collection($role->permissions)),
            created_at: $role->created_at,
            updated_at: $role->updated_at,
        );
    }

    public static function fromString(string $role): self
    {
        return new self(
            id: null,
            name: $role,
            permissions: PermissionData::collection([]),
            created_at: null,
            updated_at: null,
        );
    }
}
