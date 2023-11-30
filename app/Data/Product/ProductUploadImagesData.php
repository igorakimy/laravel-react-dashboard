<?php

namespace App\Data\Product;

use App\Enums\ProductMediaCollection;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

final class ProductUploadImagesData extends Data
{
    public function __construct(
        public UploadedFile|null $image,
    ) {
    }

    public static function fromRequest(Request $request, string $collection): self
    {
        $request->validate(self::getValidationRulesForMediaCollection(
            ProductMediaCollection::from($collection)
        ));

        return new self(
            image: $request->file('image'),
        );
    }

    public static function getValidationRulesForMediaCollection(ProductMediaCollection $collection): array
    {
        return match ($collection) {
            ProductMediaCollection::CATALOG_IMAGES, ProductMediaCollection::PRODUCT_IMAGES => [
                'image' => [
                    'required',
                    'file',
                    'max:5120',
                    'mimes:jpeg,jpg,png,gif'
                ],
            ],
            ProductMediaCollection::VECTOR_IMAGE => [
                'image' => [
                    'required',
                    'file',
                    'max:5120',
                    'mimes:svg,svg+xml'
                ]
            ]
        };
    }
}
