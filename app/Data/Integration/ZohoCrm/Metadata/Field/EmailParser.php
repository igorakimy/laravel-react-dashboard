<?php

namespace App\Data\Integration\ZohoCrm\Metadata\Field;

use Spatie\LaravelData\Data;

final class EmailParser extends Data
{
    public function __construct(
        public bool $fields_update_supported,
        public bool $record_operations_supported,
    ) {
    }
}
