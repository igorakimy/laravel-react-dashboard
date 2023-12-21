<?php

namespace App\Data\Integration\ZohoCrm\Metadata\Field;

use Spatie\LaravelData\Data;

final class ViewType extends Data
{
    public function __construct(
        public bool $view,
        public bool $edit,
        public bool $quick_create,
        public bool $create,
    ) {
    }
}
