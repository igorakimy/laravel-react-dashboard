<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

final class SuccessResponse extends JsonResponse
{
    public static function message(string $message = ''): self
    {
        return new self(
            data: $message,
            status: 200
        );
    }
}
