<?php

namespace App\Actions\Api\Product;

use App\Actions\Api\ApiAction;
use App\Data\FilteringData;
use App\Data\Product\ProductFilteringData;
use App\Data\Product\ProductForSelectData;
use App\Data\Product\ProductSortingData;
use App\Data\SortingData;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Spatie\LaravelData\DataCollection;

final class FetchProductsForSelect extends ApiAction
{
    public function handle(
        ?ProductSortingData $sorting,
        ProductFilteringData $filtering
    ): DataCollection {

        $query = Product::query();

        // apply filters
        $query = $this->applyFiltering($query, $filtering);

        // apply sorting
        $query = $this->applySorting($query, $sorting);

        $products = $query->get();

        return ProductForSelectData::collection($products);
    }

    /**
     * Apply filtering to products.
     *
     * @param  Builder  $query
     * @param  FilteringData  $filtering
     *
     * @return Builder
     */
    private function applyFiltering(Builder $query, FilteringData $filtering): Builder
    {
        if ($filtering->filters) {
            $filterCount = 1;
            foreach ($filtering->filters as $column => $filter) {
                $filterValue = trim($filter[0]);
                $query = $query->whereHas($column, function ($query) use ($filtering, $filterCount, $filterValue) {
                    $method = ($filterCount == 1 || $filtering->operator === 'AND') ? 'where' : 'orWhere';
                    $query->$method('name', 'LIKE', "%$filterValue%");
                })->when(!in_array($column, ['categories', 'type']), function ($query) use ($column, $filterValue) {
                    $query->where($column, 'LIKE', "%$filterValue%");
                });

                $filterCount++;
            }
        }
        return $query;
    }

    /**
     * Apply sorting to products.
     *
     * @param  Builder  $query
     * @param  SortingData  $sorting
     *
     * @return Builder
     */
    private function applySorting(Builder $query, SortingData $sorting): Builder
    {
        if ($sorting->column === 'categories') {
            $query = $query->orderByCategories($sorting->direction);
        } elseif ($sorting->column === 'type') {
            $query = $query->orderByType($sorting->direction);
        } else {
            $query = $query->orderBy($sorting->column, $sorting->direction);
        }

        return $query;
    }
}
