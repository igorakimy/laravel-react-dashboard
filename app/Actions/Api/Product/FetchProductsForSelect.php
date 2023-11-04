<?php

namespace App\Actions\Api\Product;

use App\Actions\Api\ApiAction;
use App\Data\Product\ProductFilteringData;
use App\Data\Product\ProductForSelectData;
use App\Data\Product\ProductSortingData;
use App\Models\Product;
use Spatie\LaravelData\DataCollection;

final class FetchProductsForSelect extends ApiAction
{
    public function handle(
        ?ProductSortingData $sorting,
        ProductFilteringData $filtering
    ): DataCollection {

        $products = Product::query();

        // apply filters
        if ($filtering->filters) {
            $filterCount = 1;
            foreach ($filtering->filters as $column => $filter) {
                $filterValue = trim($filter[0]);
                if (in_array($column, ['categories', 'type'])) {
                    $products = $products->whereHas($column, function ($query) use ($filtering, $filterCount, $filterValue) {
                        if ($filterCount == 1 || $filtering->operator === 'AND')
                            $query->where('name', 'LIKE', "%$filterValue%");
                        else
                            $query->orWhere('name', 'LIKE', "%$filterValue%");
                    });
                } else {
                    if ($filterCount == 1 || $filtering->operator === 'AND')
                        $products = $products->where($column, 'LIKE', "%$filterValue%");
                    else
                        $products = $products->orWhere($column, 'LIKE', "%$filterValue%");
                }

                $filterCount++;
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

        $products = $products->get();

        return ProductForSelectData::collection($products);
    }
}
