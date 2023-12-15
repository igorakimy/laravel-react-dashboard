<?php

namespace App\Data\Integration\ZohoInventory\Item;

use App\Data\Integration\ZohoBooks\Item\ItemData;
use Illuminate\Http\Client\Response;
use Spatie\LaravelData\Data;

final class ItemResponseData extends Data
{
    public function __construct(
        public bool $success,
        public ItemData|null $item,
        public string $message,
    ) {
    }

    public static function fromResponse(Response $response): self
    {
        $item = $response->json('item');;

        return new self(
            success: $response->successful(),
            item: $item ? ItemData::from($item) : null,
            message: $response->json('message'),
        );
    }
}
