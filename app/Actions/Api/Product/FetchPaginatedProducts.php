<?php

namespace App\Actions\Api\Product;

use App\Actions\Api\ApiAction;
use App\Data\FilteringData;
use App\Data\Product\ProductData;
use App\Data\Product\ProductFilteringData;
use App\Data\Product\ProductPaginationData;
use App\Data\Product\ProductSortingData;
use App\Data\SortingData;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Spatie\LaravelData\PaginatedDataCollection;

final class FetchPaginatedProducts extends ApiAction
{
    public function handle(
        ProductPaginationData $pagination,
        ProductSortingData $sorting,
        ProductFilteringData $filtering
    ): PaginatedDataCollection {

        $query = Product::query()->with($this->getRelations());

        $query = $this->applyFilters($query, $filtering);

        $query = $this->applySorting($query, $sorting);

        $products = $query->paginate(
            perPage: $pagination->pageSize,
            columns: $pagination->columns,
            pageName: $pagination->pageName,
            page: $pagination->currentPage
        );

        return ProductData::collection($products)->include(
            'type',
            'categories'
        );
    }

    /**
     * Apply filters.
     *
     * @param  Builder  $query
     * @param  FilteringData  $filtering
     *
     * @return Builder|Product
     */
    private function applyFilters(Builder $query, FilteringData $filtering): Builder|Product
    {
        if ($filtering->filters) {
            foreach ($filtering->filters as $column => $filter) {
                $filterValue = trim($filter[0]);
                if ($column === 'categories') {
                    $query = $query->whereHas('categories', function ($query) use ($filterValue) {
                        $query->where('name', 'LIKE', "%$filterValue%");
                    });
                } elseif($column === 'type') {
                    $query = $query->whereHas('type', function ($query) use ($filterValue) {
                        $query->where('name', 'LIKE', "%$filterValue%");
                    });
                } else {
                    $query = $query->where($column, 'LIKE', "%$filterValue%");
                }
            }
        }

        return $query;
    }

    /**
     * Apply sorting.
     *
     * @param  Builder|Product  $query
     * @param  SortingData  $sorting
     *
     * @return Builder|Product
     */
    private function applySorting(Builder|Product $query, SortingData $sorting): Builder|Product
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

    /**
     * Get list of product relations names.
     *
     * @return string[]
     */
    private function getRelations(): array
    {
        return [
            'type',
            'categories',
            'categories.parent',
            'categories.children',
            'color',
            'material',
            'vendor',
            'media'
        ];
    }
}
