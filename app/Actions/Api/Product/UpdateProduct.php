<?php

namespace App\Actions\Api\Product;

use App\Actions\Api\ApiAction;
use App\Data\Product\ProductData;
use App\Data\Product\ProductUpdateData;
use App\Models\Product;

final class UpdateProduct extends ApiAction
{
    public function __construct(
        public readonly UpdateProductMetas $updateProductMetasAction,
    ) {
    }

    public function handle(
        Product $product,
        ProductUpdateData $data,
    ): ProductData {

        $product->fill($data->except('metas', 'categories', 'integrations')->toArray());

        if($product->save()) {
            $categoriesIds = $data->categories->toCollection()->pluck('id')->toArray();

            // sync categories
            $product->categories()->sync($categoriesIds);

            // save/update meta fields values
            $this->updateProductMetasAction->handle($product, $data->metas);
        }

        return ProductData::from($product)->include(
            'type',
            'color',
            'metas',
            'vendor',
            'material',
        );
    }
}
