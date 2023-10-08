<?php

namespace App\Data\Product;

use App\Data\Category\CategoryData;
use App\Data\Color\ColorData;
use App\Data\Material\MaterialData;
use App\Data\Type\TypeData;
use App\Data\Vendor\VendorData;
use App\Models\Category;
use App\Models\Color;
use App\Models\Material;
use App\Models\Type;
use App\Models\Vendor;
use Exception;
use Illuminate\Http\Request;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
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

        public ColorData|null $color,
        public MaterialData|null $material,
        public VendorData|null $vendor,
        public TypeData|null $type,

        #[DataCollectionOf(CategoryData::class)]
        public DataCollection $categories,

        public string|null $caption,
        public string|null $description,
    ) {}

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
            color: ColorData::from(Color::findOrFail($request->input('color'))),
            material: MaterialData::from(Material::findOrFail($request->input('material'))),
            vendor: VendorData::from(Vendor::findOrFail($request->input('vendor'))),
            type: TypeData::from(Type::findOrFail($request->input('type'))),
            categories: CategoryData::collection(Category::findMany($request->input('categories'))),
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
            'color' => ['nullable', 'integer', 'exists:colors,id'],
            'material' => ['nullable', 'integer', 'exists:materials,id'],
            'vendor' => ['nullable', 'integer', 'exists:vendors,id'],
            'type' => ['nullable', 'integer', 'exists:types,id'],
            'categories' => ['nullable', 'array'],
            'caption' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
        ];
    }
}
