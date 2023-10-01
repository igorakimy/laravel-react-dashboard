<?php

namespace App\Data;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class PaginationData extends Data
{
    public const DEFAULT_PAGE_SIZE = 10;
    public const DEFAULT_SORT_DIRECTION = 'ascend';

    public function __construct(
        public int $currentPage,
        public int $pageSize,
        public string $sortDirection,
        public string $sortColumn,
    ) {
    }

    public static function fromRequest(Request $request): static
    {
        $request->validate(static::rules());

        return new static(
            currentPage: $request->input('pagination.current', 1),
            pageSize: $request->input('pagination.pageSize', static::DEFAULT_PAGE_SIZE),
            sortDirection: str_replace('end', '', $request->input('order', static::DEFAULT_SORT_DIRECTION)),
            sortColumn: $request->input('field', 'id'),
        );
    }

    public static function rules(): array
    {
        return [
            'pagination' => [
                'array'
            ],
            'pagination.current' => [
                'integer',
            ],
            'pagination.pageSize' => [
                'integer',
            ],
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
