<?php

namespace App\Data\Integration\ZohoBooks\Document;

use Illuminate\Http\Client\Response;
use Spatie\LaravelData\Data;

final class DocumentResponseData extends Data
{
    public function __construct(
        public bool $success,
        public DocumentData|null $document,
        public string $message
    ) {
    }

    public static function fromResponse(Response $response): self
    {
        return new self(
            success: $response->successful(),
            document: $response->json('document')
                ? DocumentData::from($response->json('document'))
                : null,
            message: $response->json('message')
        );
    }
}
