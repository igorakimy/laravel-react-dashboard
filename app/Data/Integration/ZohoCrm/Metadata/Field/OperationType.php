<?php

namespace App\Data\Integration\ZohoCrm\Metadata\Field;

use Spatie\LaravelData\Data;

final class OperationType extends Data
{
    public function __construct(
        public bool $web_update,
        public bool $api_create,
        public bool $web_create,
        public bool $api_update,
    ) {
    }
}
