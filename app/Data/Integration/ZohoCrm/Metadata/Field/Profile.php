<?php

namespace App\Data\Integration\ZohoCrm\Metadata\Field;

use Spatie\LaravelData\Data;

final class Profile extends Data
{
    public function __construct(
        public string $permission_type,
        public string $name,
        public string $id,
    ) {
    }
}
