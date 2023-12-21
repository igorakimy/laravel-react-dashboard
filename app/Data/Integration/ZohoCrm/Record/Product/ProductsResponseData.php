<?php

namespace App\Data\Integration\ZohoCrm\Record\Product;

use Illuminate\Http\Client\Response;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

final class ProductsResponseData extends Data
{
    public function __construct(
        public bool $success,

        #[DataCollectionOf(ProductData::class)]
        public DataCollection|null $products,

        public string|null $message,
    ) {
    }

    public static function fromResponse(Response $response): self
    {
        return new self(
            success: $response->successful(),
            products: ProductData::collection($response->json('data')),
            message: $response->json('message'),
        );
    }
}
