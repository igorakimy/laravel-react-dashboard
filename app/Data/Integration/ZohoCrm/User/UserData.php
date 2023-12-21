<?php

namespace App\Data\Integration\ZohoCrm\User;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

final class UserData extends Data
{
    public function __construct(
        public string $id,
        public string|Optional $name,
        public string|Optional $email,
    ){
    }
}
