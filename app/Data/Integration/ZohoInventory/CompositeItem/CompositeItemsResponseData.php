<?php

namespace App\Data\Integration\ZohoInventory\CompositeItem;

use Illuminate\Http\Client\Response;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

final class CompositeItemsResponseData extends Data
{
    public function __construct(
        public bool $success,

        #[DataCollectionOf(CompositeItemData::class)]
        public DataCollection $composite_items,

        public string $message,
    ) {
    }

    public static function fromResponse(Response $response): self
    {
        return new self(
            success: $response->successful(),
            composite_items: CompositeItemData::collection($response->json('composite_items')),
            message: $response->json('message', ''),
        );
    }
}
