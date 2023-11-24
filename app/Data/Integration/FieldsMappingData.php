<?php

namespace App\Data\Integration;

use Illuminate\Http\Request;
use Spatie\LaravelData\Data;

final class FieldsMappingData extends Data
{
    public function __construct(
        public array $fields
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        $request->validate(self::rules());

        return new self(
            fields: $request->input('fields', [])
        );
    }

    public static function rules(): array
    {
        return [
            'fields' => ['required', 'array']
        ];
    }
}
