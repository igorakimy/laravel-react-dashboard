<?php

namespace App\Data\Integration\ZohoInventory\Auth;

use Illuminate\Http\Client\Response;
use Spatie\LaravelData\Data;

final class AccessTokenData extends Data
{
    public function __construct(
        public bool $success,
        public ?string $access_token,
        public ?string $refresh_token = null,
        public ?int $expires_in = null,
        public ?string $message = null,
    ) {
    }

    public static function fromResponse(Response $response): self
    {
        $errorMsg = $response->json('error')
                    ?? $response->json('message')
                       ?? $response->toException()?->getMessage();

        return new self(
            success: $response->successful(),
            access_token: $response->json('access_token'),
            refresh_token: $response->json('refresh_token'),
            expires_in: $response->json('expires_in'),
            message: $errorMsg,
        );
    }
}
