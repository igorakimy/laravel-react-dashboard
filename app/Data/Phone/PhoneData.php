<?php

namespace App\Data\Phone;

use Spatie\LaravelData\Data;

final class PhoneData extends Data
{
    public function __construct(
        public string $number,
        public int|null $user_id,
        public bool $active,
    ) {
    }
}
