<?php

namespace App\Actions\Api\Product;

use App\Actions\Api\ApiAction;
use App\Data\Product\ProductUploadImagesData;
use App\Models\Product;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

final class UploadProductImage extends ApiAction
{
    /**
     * @throws FileIsTooBig
     * @throws FileDoesNotExist
     */
    public function handle(Product $product, ProductUploadImagesData $uploadImagesData): Media|null
    {
        $media = null;

        if ($uploadImagesData->catalogImage) {
            $media = $product->addMedia($uploadImagesData->catalogImage)
                             ->toMediaCollection("catalog_images", config('media-library.disk_name'));
        } elseif ($uploadImagesData->productImage) {
            $media = $product->addMedia($uploadImagesData->productImage)
                             ->toMediaCollection("product_images", config('media-library.disk_name'));
        } elseif ($uploadImagesData->vectorImage) {
            $media = $product->addMedia($uploadImagesData->vectorImage)
                             ->toMediaCollection("vector_images", config('media-library.disk_name'));
        }

        return $media;
    }
}
