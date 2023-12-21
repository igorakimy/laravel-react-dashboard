<?php

namespace App\Data\Integration\ZohoCrm\Metadata\Field;

use Spatie\LaravelData\Data;

final class AssociatedModule extends Data
{
    public function __construct(
        public string $module,
        public string $id,
    ) {
    }
}
