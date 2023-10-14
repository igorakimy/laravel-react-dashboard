<?php

namespace App\Data\Product;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

final class ProductUploadImagesData extends Data
{
    public function __construct(
        public UploadedFile|null $catalogImage,
        public UploadedFile|null $productImage,
        public UploadedFile|null $vectorImage,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            catalogImage: $request->has('catalog_image') ? $request->file('catalog_image') : null,
            productImage: $request->has('product_image') ? $request->file('product_image') : null,
            vectorImage: $request->has('vector_image') ? $request->file('vector_image') : null
        );
    }
}
