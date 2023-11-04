<?php

namespace App\Data\Product;

use Spatie\LaravelData\Data;

final class ProductForSelectData extends Data
{
    public function __construct(
        public int|null $id,
        public string $name,
        public string $sku,
    ) {
    }
}
