<?php

namespace App\Actions\Api\Product;

use App\Actions\Api\ApiAction;
use App\Data\Product\ProductUploadImagesData;
use App\Enums\ProductMediaCollection;
use App\Models\Product;
use Exception;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

final class UploadProductImage extends ApiAction
{
    /**
     * @throws FileIsTooBig
     * @throws FileDoesNotExist
     * @throws Exception
     */
    public function handle(Product $product, ProductUploadImagesData $uploadImagesData, string $collection): Media|null
    {
        $media = null;
        $imageFile = $uploadImagesData->image;

        if ( ! in_array($collection, ProductMediaCollection::values())) {
            throw new Exception("Media collection doesn't exists");
        }

        if ($uploadImagesData->image) {
            $diskName = config('media-library.disk_name');
            $media = $product->addMedia($imageFile)
                             ->toMediaCollection($collection, $diskName);
        }

        return $media;
    }
}
