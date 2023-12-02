<?php

namespace App\Actions\Api\Product;

use App\Data\Product\ProductData;
use App\Models\Product;
use Spatie\LaravelData\Data;

final class ShowProduct extends Data
{
    public function handle(Product|int $product): ProductData
    {
        if ( ! $product instanceof Product) {
            /** @var Product $product */
            $product = Product::query()->findOrFail($product);
        }

        return ProductData::from($product)->include(
            'type',
            'metas',
            'color',
            'media',
            'vendor',
            'material',
            'categories',
            'integrations',
        );
    }
}
