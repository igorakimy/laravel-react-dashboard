<?php

namespace App\Actions\Api\Product;

use App\Actions\Api\ApiAction;
use App\Data\Product\ProductData;
use App\Data\Product\ProductStoreData;
use App\Models\Product;

final class StoreProduct extends ApiAction
{
    public function handle(ProductStoreData $data): ProductData
    {
        // create new product.
        $product = new Product();

        $product->fill($data->except('metas', 'categories')->toArray());

        // attach categories to new product.
        $product->categories()->attach($data->categories->toCollection()->pluck('id'));

        $product->save();

        return ProductData::from($product)->include('categories');
    }
}
