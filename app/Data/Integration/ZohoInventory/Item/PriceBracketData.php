<?php

namespace App\Data\Integration\ZohoInventory\Item;

use Spatie\LaravelData\Data;

final class PriceBracketData extends Data
{
    public function __construct(
        public float $start_quantity,
        public float $end_quantity,
        public float $pricebook_rate,
    ) {
    }
}
