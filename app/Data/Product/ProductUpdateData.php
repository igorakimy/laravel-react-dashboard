<?php

namespace App\Data\Product;

use App\Data\Category\CategoryData;
use App\Data\Integration\IntegrationData;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Support\Validation\References\RouteParameterReference;

class ProductUpdateData extends Data
{
    public function __construct(
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

        public int|null|Optional $color_id,
        public int|null|Optional $material_id,
        public int|null|Optional $vendor_id,
        public int|Optional $type_id,

        #[DataCollectionOf(CategoryData::class)]
        public DataCollection $categories,

        #[DataCollectionOf(ProductMetaData::class)]
        public DataCollection|null $metas,

        #[DataCollectionOf(IntegrationData::class)]
        public DataCollection|null $integrations,

        public string|null $caption,
        public string|null $description,
    ) {
    }

    /**
     * @param  Request  $request
     *
     * @return self
     * @throws Exception
     */
    public static function fromRequest(Request $request): self
    {
        $request->validate(self::rules());

        return new self(
            name: $request->input('name'),
            sku: $request->input('sku'),
            quantity: $request->input('quantity', 0),
            cost_price: $request->input('cost_price'),
            selling_price: $request->input('selling_price'),
            margin: $request->input('margin'),
            width: $request->input('width'),
            height: $request->input('height'),
            weight: $request->input('weight'),
            barcode: $request->input('barcode'),
            location: $request->input('location'),
            color_id: $request->input('color_id', Optional::create()),
            material_id: $request->input('material_id', Optional::create()),
            vendor_id: $request->input('vendor_id', Optional::create()),
            type_id: $request->input('type_id', Optional::create()),
            categories: CategoryData::collection(Category::findMany($request->input('categories'))),
            metas: $request->input('metas')
                ? ProductMetaData::collection(collect($request->input('metas'))->map(function($meta) {
                    return new ProductMetaData(Optional::create(), $meta['meta_id'], $meta['value']);
                })->toArray())
                : null,
            integrations: $request->input('integrations'),
            caption: $request->input('caption'),
            description: $request->input('description'),
        );
    }

    /**
     * @return array[]
     * @throws Exception
     */
    public static function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'sku' => [
                'required',
                'string',
                'max:150',
                new Unique(
                    table: 'products',
                    column: 'sku',
                    ignore: new RouteParameterReference('product', 'id')
                )
            ],
            'quantity' => ['required', 'integer', 'min:0', 'max:999999999'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'margin' => ['numeric', 'min:0'],
            'width' => ['required', 'numeric', 'min:0', 'max:999999999'],
            'height' => ['required', 'numeric', 'min:0', 'max:999999999'],
            'weight' => ['required', 'numeric', 'min:0', 'max:999999999'],
            'barcode' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'color_id' => ['nullable', 'integer', 'exists:colors,id'],
            'material_id' => ['nullable', 'integer', 'exists:materials,id'],
            'vendor_id' => ['nullable', 'integer', 'exists:vendors,id'],
            'type_id' => ['required', 'integer', 'exists:types,id'],
            'categories' => ['nullable', 'array'],
            'caption' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
        ];
    }
}
