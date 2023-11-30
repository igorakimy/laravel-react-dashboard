<?php

namespace App\Actions\Api\Product;

use App\Data\Product\ProductMetaData;
use App\Models\Product;
use Spatie\LaravelData\DataCollection;

final class UpdateProductMetas
{
    public function handle(Product $product, ?DataCollection $metaFields): void
    {
        if ($metaFields) {
            /** @var ProductMetaData $metaField */
            foreach ($metaFields as $metaField) {
                $productMeta = $product->productMetas()->firstOrCreate([
                    'product_id' => $product->id,
                    'meta_id' => $metaField->meta_id
                ]);

                $productMeta->update([
                    'value' => $metaField->value
                ]);
            }
        }
    }
}
