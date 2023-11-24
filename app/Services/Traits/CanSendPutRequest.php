<?php

namespace App\Services\Traits;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;

trait CanSendPutRequest
{
    public function put(
        PendingRequest $request,
        string $url,
        array $payload,
        ?array $queryParams = null,
        ?array $headers = null
    ): Response {
        if ( ! $headers) {
            $headers = [];
        }

        if ( ! $queryParams) {
            $queryParams = [];
        }

        return $request
            ->withHeaders($headers)
            ->withQueryParameters($queryParams)
            ->put($url, $payload);
    }
}
