<?php

namespace App\Data;

use Illuminate\Http\Request;
use Spatie\LaravelData\Data;

class PaginationData extends Data
{
    public const DEFAULT_PAGE_SIZE = 10;

    public function __construct(
        public int $currentPage,
        public int $pageSize,
        public array $columns = ['*'],
        public string $pageName = 'page',
    ) {
    }

    public static function fromRequest(Request $request): static
    {
        $request->validate(static::rules());

        return new static(
            currentPage: $request->input('pagination.current', 1),
            pageSize: $request->input('pagination.pageSize', static::DEFAULT_PAGE_SIZE),
            columns: $request->input('pagination.columns', ['*']),
            pageName: $request->input('pagination.pageName', 'page'),
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
        ];
    }
}
