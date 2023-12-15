<?php

namespace App\Data\Integration\ZohoInventory;

use Spatie\LaravelData\Data;

final class PackageDetailsData extends Data
{
    public function __construct(
       public string $dimension_unit,
       public float $height,
       public float $length,
       public float $weight,
       public string $weight_unit,
       public float $width
    ) {
    }
}
