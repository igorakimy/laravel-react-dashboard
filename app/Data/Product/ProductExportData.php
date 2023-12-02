<?php

namespace App\Data\Product;

use Illuminate\Http\Request;
use Spatie\LaravelData\Data;

final class ProductExportData extends Data
{
    public function __construct(
        public string $decimal_format,
        public string $export_as,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        $request->validate(self::rules());

        return new self(
            decimal_format: $request->input('decimal_format'),
            export_as: $request->input('export_as'),
        );
    }

    public static function rules(): array
    {
        return [
            'decimal_format' => ['required', 'string'],
            'export_as' => ['required', 'string'],
        ];
    }
}
