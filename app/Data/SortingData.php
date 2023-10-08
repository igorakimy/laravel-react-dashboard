<?php

namespace App\Data;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class SortingData extends Data
{
    public const DEFAULT_SORT_DIRECTION = 'ascend';

    public function __construct(
        public string $direction,
        public string $column,
    ) {
    }

    public static function fromRequest(Request $request): static
    {
        $request->validate(static::rules());

        return new static(
            direction: str_replace('end', '', $request->input('order', static::DEFAULT_SORT_DIRECTION)),
            column: $request->input('field', 'id'),
        );
    }

    public static function rules(): array
    {
        return [
            'order' => [
                'string',
                'nullable',
                Rule::in(['ascend', 'descend'])
            ],
            'field' => [
                'string',
                'nullable',
            ],
        ];
    }
}
