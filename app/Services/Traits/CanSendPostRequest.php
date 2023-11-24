<?php

namespace App\Services\Traits;

use App\Helpers\AttachmentHelper;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;

trait CanSendPostRequest
{
    public function post(
        PendingRequest $request,
        string $url,
        array $payload = null,
        bool $payloadInQueryParams = false,
        AttachmentHelper|null $attachment = null,
    ): Response {
        $queryParams = [];

        if (null === $payload) {
            $payload = [];
        }

        if ($payloadInQueryParams) {
            $queryParams = $payload;
            $payload = [];
        }

        $request = $request->withQueryParameters($queryParams);

        if ($attachment) {
            $request = $request->attach(
                $attachment->name,
                $attachment->content,
                $attachment->filename,
            );
        }

        return $request->post($url, $payload);
    }
}
