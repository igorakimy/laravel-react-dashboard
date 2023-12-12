<?php

namespace App\Data\Import;

use Spatie\LaravelData\Data;

final class ImportHeadersData extends Data
{
    public function __construct(
        public array $headers
    ) {
    }

    public static function fromModel(array $headers): self
    {
        return new self(
            headers: $headers,
        );
    }
}
