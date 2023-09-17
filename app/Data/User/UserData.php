<?php

namespace App\Data\User;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

class UserData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,

        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public ?Carbon $created_at,

        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public ?Carbon $updated_at,
    ) {}
}
