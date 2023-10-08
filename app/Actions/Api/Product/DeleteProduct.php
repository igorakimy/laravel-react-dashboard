<?php

namespace App\Actions\Api\Product;

use App\Data\Product\ProductData;
use App\Models\Product;
use Spatie\LaravelData\Data;

final class DeleteProduct extends Data
{
    public function handle(Product|int $product): ProductData
    {
        if ( ! $product instanceof Product) {
            $product = Product::query()->findOrFail($product);
        }

        $product?->delete();

        return ProductData::from($product);
    }
}
