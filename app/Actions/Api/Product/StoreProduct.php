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
        $product = new Product();

        $product->name = $data->name;
        $product->sku = $data->sku;
        $product->quantity = $data->quantity;
        $product->cost_price = $data->cost_price;
        $product->selling_price = $data->selling_price;
        $product->margin = $data->margin;
        $product->width = $data->width;
        $product->height = $data->height;
        $product->weight = $data->weight;
        $product->barcode = $data->barcode;
        $product->location = $data->location;
        $product->color_id = $data->color->id;
        $product->material_id = $data->material->id;
        $product->vendor_id = $data->vendor->id;
        $product->type_id = $data->type->id;
        $product->caption = $data->caption;
        $product->description = $data->description;

        $product->save();

        $product->categories()->attach($data->categories->toCollection()->pluck('id'));

        $product->load('categories');

        return ProductData::from($product);
    }
}
