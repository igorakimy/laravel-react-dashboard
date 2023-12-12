<?php

namespace App\Data\Product;

use Illuminate\Http\Request;
use Spatie\LaravelData\Data;

final class ProductImportData extends Data
{
    public function __construct(
        public string $filepath,
        public array $mapped_columns,
        public bool $save_template,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            filepath: $request->input('filepath'),
            mapped_columns: $request->input('mapping'),
            save_template: $request->input('save_template', false),
        );
    }
}
