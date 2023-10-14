<?php

namespace App\Actions\Api\Product;

use App\Data\Product\ProductData;
use App\Models\Product;
use Spatie\LaravelData\Data;

final class ShowProduct extends Data
{
    public function handle(Product|int $product): ProductData
    {
        $relations = [
            'type',
            'categories',
            'color',
            'material',
            'vendor',
            'media'
        ];

        if ( ! $product instanceof Product) {
            /** @var Product $product */
            $product = Product::query()
                              ->with($relations)
                              ->findOrFail($product);
        }

        $product->load($relations);

        return ProductData::from($product);
    }
}
