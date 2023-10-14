<?php

namespace App\Actions\Api\Category;

use App\Actions\Api\ApiAction;
use App\Data\Category\CategoryData;
use App\Data\Category\CategoryPaginationData;
use App\Data\Category\CategorySortingData;
use App\Models\Category;
use Spatie\LaravelData\PaginatedDataCollection;

final class FetchPaginatedCategories extends ApiAction
{
    public function handle(
        CategoryPaginationData $pagination,
        CategorySortingData $sorting,
    ): PaginatedDataCollection {

        $categories = Category::query()
                              ->with(['parent', 'children'])
                              ->orderBy($sorting->column, $sorting->direction)
                              ->paginate(
                                  $pagination->pageSize,
                                  ['*'],
                                  'page',
                                  $pagination->currentPage
                              );

        return CategoryData::collection($categories);
    }
}
