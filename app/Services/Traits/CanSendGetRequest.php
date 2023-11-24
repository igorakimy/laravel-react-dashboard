<?php

namespace App\Services\Traits;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;

trait CanSendGetRequest
{
    public function get(
        PendingRequest $request,
        string $url,
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
            ->get($url);
    }
}
