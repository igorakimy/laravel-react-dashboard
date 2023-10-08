<?php

namespace App\Data\Color;

use Spatie\LaravelData\Data;

final class ColorData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string|null $hex,
        public string|null $rgb,
    ) {
    }
}
