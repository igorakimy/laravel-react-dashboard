<?php

namespace App\Data\Product;

use Spatie\LaravelData\Data;

final class ProductExportDoneData extends Data
{
    public function __construct(
        public string $file,
        public array $headers,
    ) {
    }
}
