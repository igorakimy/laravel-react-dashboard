<?php

namespace App\Data\Integration\ZohoBooks\Document;

use Illuminate\Http\Client\Response;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

final class DocumentsResponseData extends Data
{
    public function __construct(
        public bool $success,

        #[DataCollectionOf(DocumentData::class)]
        public DataCollection $documents,

        public string $message
    ) {
    }

    public static function fromResponse(Response $response): self
    {
        return new self(
            success: $response->successful(),
            documents: DocumentData::collection($response->json('documents')),
            message: $response->json('message')
        );
    }
}
