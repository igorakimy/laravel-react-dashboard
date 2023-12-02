<?php

namespace App\Data\Product;

use App\Data\Category\CategoryData;
use App\Data\Color\ColorData;
use App\Data\Integration\IntegrationData;
use App\Data\Material\MaterialData;
use App\Data\Media\MediaData;
use App\Data\Type\TypeData;
use App\Data\Vendor\VendorData;
use App\Models\Product;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Optional;

final class ProductData extends Data
{
    public function __construct(
        public int|null|Optional $id,
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

        public ColorData|Lazy|null $color,
        public MaterialData|Lazy|null $material,
        public VendorData|Lazy|null $vendor,
        public TypeData|Lazy|null $type,

        #[DataCollectionOf(CategoryData::class)]
        public DataCollection|Lazy|null $categories,

        #[DataCollectionOf(ProductMetaData::class)]
        public DataCollection|Lazy|null $metas,

        #[DataCollectionOf(IntegrationData::class)]
        public DataCollection|Lazy|null $integrations,

        public string|null $caption,
        public string|null $description,

        #[DataCollectionOf(MediaData::class)]
        public DataCollection|Lazy|null $media,
    ) {
    }

    public static function fromModel(Product $product): self
    {
        return new self(
            id: $product->id,
            name: $product->name,
            sku: $product->sku,
            quantity: $product->quantity,
            cost_price: $product->cost_price,
            selling_price: $product->selling_price,
            margin: $product->margin,
            width: $product->width,
            height: $product->height,
            weight: $product->weight,
            barcode: $product->barcode,
            location: $product->location,
            color: Lazy::create(fn() => ColorData::optional($product->color)),
            material: Lazy::create(fn() => MaterialData::optional($product->material)),
            vendor: Lazy::create(fn() => VendorData::optional($product->vendor)),
            type: Lazy::create(fn() => TypeData::optional($product->type)),
            categories: Lazy::create(fn() => CategoryData::collection($product->categories)),
            metas: Lazy::create(fn() => ProductMetaData::collection($product->productMetas)),
            integrations: Lazy::create(fn() => IntegrationData::collection($product->integrations)),
            caption: $product->caption,
            description: $product->description,
            media: Lazy::create(fn() => MediaData::collection($product->media)),
        );
    }
}
