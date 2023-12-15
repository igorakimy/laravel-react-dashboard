<?php

namespace App\Data\Integration\ZohoInventory\Item;

use Illuminate\Http\Client\Response;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

final class ItemsResponseData extends Data
{
    public function __construct(
        public bool $success,

        #[DataCollectionOf(ItemData::class)]
        public DataCollection $items,

        public string $message,
    ) {
    }

    public static function fromResponse(Response $response): self
    {
        $items = $response->json('items');

        return new self(
            success: $response->successful(),
            items: $items ? ItemData::collection($items) : null,
            message: $response->json('message'),
        );
    }
}
