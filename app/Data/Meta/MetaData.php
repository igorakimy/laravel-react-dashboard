<?php

namespace App\Data\Meta;

use App\Enums\FieldType;
use Illuminate\Http\Request;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

final class MetaData extends Data
{
    public function __construct(
        public string $name,
        public string|Optional $slug,
        public FieldType $field_type,
        public bool|Optional $is_required,
        public string|null|Optional $value,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->input('name'),
            slug: Optional::create(),
            field_type: $request->input('field_type'),
            is_required: $request->input('is_required', false),
            value: $request->input('value'),
        );
    }
}
