<?php

namespace App\Data\Integration;

use Spatie\LaravelData\Data;

final class IntegrationData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
    ) {
    }
}
