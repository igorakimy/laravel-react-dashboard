<?php

namespace App\Data\LocalField;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

final class LocalFieldData extends Data
{
    public function __construct(
        public Optional|int $id,
        public string $name,
        public string $slug,
        public string $field_type,
        public int $order,
        public ?string $default_value,
        public ?array $validations,
        public ?array $properties,
        public bool $permanent,
    ) {
    }
}
