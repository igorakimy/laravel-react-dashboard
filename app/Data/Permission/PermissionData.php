<?php

namespace App\Data\Permission;

use App\Models\Permission;
use Spatie\LaravelData\Data;

final class PermissionData extends Data
{
    public function __construct(
        public ?int $id,
        public string $name,
        public string $display_name,
    ) {
    }

    public static function fromModel(Permission $permission): self
    {
        return new self(
            id: $permission->id,
            name: $permission->name,
            display_name: $permission->display_name,
        );
    }

}
