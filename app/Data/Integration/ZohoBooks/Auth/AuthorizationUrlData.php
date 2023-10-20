<?php

namespace App\Data\Integration\ZohoBooks\Auth;

use Spatie\LaravelData\Data;

final class AuthorizationUrlData extends Data
{
    public function __construct(
        public string $auth_url,
    ) {
    }

    public static function fromString(string $authUrl): self
    {
        return new self(
            auth_url: $authUrl,
        );
    }
}
