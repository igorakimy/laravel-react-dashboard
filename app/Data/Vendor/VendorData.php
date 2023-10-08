<?php

namespace App\Data\Vendor;

use Spatie\LaravelData\Data;

final class VendorData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
    ) {
    }
}
