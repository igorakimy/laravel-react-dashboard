<?php

namespace App\Data\Product;

use App\Data\Category\CategoryData;
use App\Data\Color\ColorData;
use App\Data\Material\MaterialData;
use App\Data\Media\MediaData;
use App\Data\Type\TypeData;
use App\Data\Vendor\VendorData;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

final class ProductData extends Data
{
    public function __construct(
        public int|null $id,
        public string $name,
        public string $sku,

        public int $quantity,

        public float|null $cost_price,
        public float|null $selling_price,

        public float|null $margin,

        public float|null $width,
        public float|null $height,
        public float|null $weight,

        public string|null $barcode,
        public string|null $location,

        public ColorData|null $color,
        public MaterialData|null $material,
        public VendorData|null $vendor,
        public TypeData|null $type,

        #[DataCollectionOf(CategoryData::class)]
        public DataCollection|null $categories,

        public string|null $caption,
        public string|null $description,

        #[DataCollectionOf(MediaData::class)]
        public DataCollection|null $media,
    ) {
    }
}
