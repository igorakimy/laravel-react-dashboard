<?php

namespace App\Data\Product;

use App\Data\Category\CategoryData;
use App\Models\Category;
use Illuminate\Http\Request;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Optional;

class ProductStoreData extends Data
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
        public int|null $type_id,

        #[DataCollectionOf(CategoryData::class)]
        public DataCollection $categories,

        public string|null $caption,
        public string|null $description,
    ) {}

    /**
     * @param  Request  $request
     *
     * @return self
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
            caption: $request->input('caption'),
            description: $request->input('description'),
        );
    }

    /**
     * @return array[]
     */
    public static function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'sku' => ['required', 'string', 'max:150', 'unique:products,sku'],
            'quantity' => ['required', 'integer', 'min:0', 'max:999999999'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'margin' => ['numeric', 'min:0'],
            'width' => ['required', 'numeric', 'min:0'],
            'height' => ['required', 'numeric', 'min:0'],
            'weight' => ['required', 'numeric', 'min:0'],
            'barcode' => ['nullable', 'string'],
            'location' => ['nullable', 'string'],
            'color_id' => ['nullable', 'integer', 'exists:colors,id'],
            'material_id' => ['nullable', 'integer', 'exists:materials,id'],
            'vendor_id' => ['nullable', 'integer', 'exists:vendors,id'],
            'type_id' => ['nullable', 'integer', 'exists:types,id'],
            'categories' => ['nullable', 'array'],
            'caption' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
        ];
    }
}
