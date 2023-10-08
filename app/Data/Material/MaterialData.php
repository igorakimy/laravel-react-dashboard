<?php

namespace App\Data\Material;

use Spatie\LaravelData\Data;

final class MaterialData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
    ) {
    }
}
