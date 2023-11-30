<?php

namespace App\Data\Product;

use Illuminate\Http\Request;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

final class ProductMetaData extends Data
{
    public function __construct(
        public int|Optional $product_id,
        public int $meta_id,
        public string|null $value
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        $request->validate(self::rules());

        return new self(
            product_id: $request->input('product_id', Optional::create()),
            meta_id: $request->input('meta_id'),
            value: $request->input('value'),
        );
    }

    public static function rules(): array
    {
        return [
            'meta_id' => ['required', 'integer'],
            'value' => ['nullable']
        ];
    }
}
