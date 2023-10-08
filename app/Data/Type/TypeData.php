<?php

namespace App\Data\Type;

use Spatie\LaravelData\Data;

final class TypeData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
    ) {
    }
}
