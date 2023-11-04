<?php

namespace App\Data;

use Illuminate\Http\Request;
use Spatie\LaravelData\Data;

class FilteringData extends Data
{
    public function __construct(
        public array $filters,
        public string $operator = "AND",
    ) {
    }

    public static function fromRequest(Request $request): static
    {
        $request->validate(static::rules());

        return new static(
            filters: $request->input('filters', []),
            operator: $request->input('operator', 'AND'),
        );
    }

    public static function rules(): array
    {
        return [
            'filters' => ['nullable', 'array']
        ];
    }
}
