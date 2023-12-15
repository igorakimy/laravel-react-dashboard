<?php

namespace App\Data\Integration\ZohoInventory\Document;

use Spatie\LaravelData\Data;

final class TransactionData extends Data
{
    public function __construct(
        public string $entity,
        public string $entity_formatted,
        public string $entity_id,
        public string $entity_name
    ) {
    }
}
