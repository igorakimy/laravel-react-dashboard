<?php

namespace App\Actions\Api\Product;

use App\Actions\Api\ApiAction;
use App\Data\Product\ProductData;
use App\Data\Product\ProductFilteringData;
use App\Data\Product\ProductPaginationData;
use App\Data\Product\ProductSortingData;
use App\Models\Product;
use Spatie\LaravelData\PaginatedDataCollection;

final class FetchPaginatedProducts extends ApiAction
{
    public function handle(
        ProductPaginationData $pagination,
        ProductSortingData $sorting,
        ProductFilteringData $filtering
    ): PaginatedDataCollection {
        $products = Product::query()
                           ->with(['type', 'categories', 'color', 'material', 'vendor']);

        // apply filters
        if ($filtering->filters) {
            foreach ($filtering->filters as $column => $filter) {
                $filterValue = trim($filter[0]);
                if ($column === 'categories') {
                    $products = $products->whereHas('categories', function ($query) use ($filterValue) {
                        $query->where('name', 'LIKE', "%$filterValue%");
                    });
                } elseif($column === 'type') {
                    $products = $products->whereHas('type', function ($query) use ($filterValue) {
                        $query->where('name', 'LIKE', "%$filterValue%");
                    });
                } else {
                    $products = $products->where($column, 'LIKE', "%$filterValue%");
                }
            }
        }

        // apply sorting
        if ($sorting->column === 'categories') {
            $products = $products->orderByCategories($sorting->direction);
        } elseif ($sorting->column === 'type') {
            $products = $products->orderByType($sorting->direction);
        } else {
            $products = $products->orderBy($sorting->column, $sorting->direction);
        }

        // paginate
        $products = $products->paginate(
            $pagination->pageSize,
            ['*'],
            'page',
            $pagination->currentPage
        );

        return ProductData::collection($products);
    }
}
