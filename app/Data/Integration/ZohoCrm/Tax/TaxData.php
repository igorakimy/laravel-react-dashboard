<?php

namespace App\Data\Integration\ZohoCrm\Tax;

use Illuminate\Support\Optional;
use Spatie\LaravelData\Data;

final class TaxData extends Data
{
    public function __construct(
        public string|Optional $id,
        public string|Optional $value,
    ) {
    }
}
