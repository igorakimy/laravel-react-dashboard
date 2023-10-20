<?php

namespace App\Http\Controllers\Api;

use App\Actions\Api\Category\DeleteCategory;
use App\Actions\Api\Category\FetchCategoriesForSelect;
use App\Actions\Api\Category\FetchCategoriesForTree;
use App\Actions\Api\Category\FetchPaginatedCategories;
use App\Actions\Api\Category\ShowCategory;
use App\Actions\Api\Category\StoreCategory;
use App\Actions\Api\Category\UpdateCategory;
use App\Data\Category\CategoryPaginationData;
use App\Data\Category\CategorySortingData;
use App\Data\Category\CategoryStoreData;
use App\Data\Category\CategoryUpdateData;
use App\Http\Controllers\ApiController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as RespCode;
use Throwable;

final class CategoryController extends ApiController
{
    public function __construct(
        public FetchPaginatedCategories $fetchPaginatedCategoriesAction,
        public FetchCategoriesForSelect $fetchCategoriesForSelectAction,
        public FetchCategoriesForTree $fetchCategoriesForTreeAction,
        public ShowCategory $showCategoryAction,
        public StoreCategory $storeCategoryAction,
        public UpdateCategory $updateCategoryAction,
        public DeleteCategory $deleteCategoryAction,
    ) {
    }

    /**
     * Show list of categories.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        $pagination = CategoryPaginationData::fromRequest($request);
        $sorting = CategorySortingData::fromRequest($request);

        $categories = match ($request->input('kind')) {
            'select' => $this->fetchCategoriesForSelectAction->handle(),
            'tree' => $this->fetchCategoriesForTreeAction->handle(),
            default => $this->fetchPaginatedCategoriesAction->handle(
                $pagination,
                $sorting
            )
        };

        return response($categories);
    }

    /**
     * Show the category.
     *
     * @param  Category  $category
     *
     * @return Response
     */
    public function show(Category $category): Response
    {
        $categoryData = $this->showCategoryAction->handle($category);

        return response($categoryData);
    }

    /**
     * Create new category.
     *
     * @param  Request  $request
     *
     * @return Response
     * @throws Throwable
     */
    public function store(Request $request): Response
    {
        $data = CategoryStoreData::fromRequest($request);

        $category = $this->storeCategoryAction->handle($data);

        return response($category, RespCode::HTTP_CREATED);
    }

    /**
     * Update category.
     *
     * @param  Request  $request
     * @param  Category  $category
     *
     * @return Response
     * @throws Throwable
     */
    public function update(Request $request, Category $category): Response
    {
        $data = CategoryUpdateData::fromRequest($request);

        $categoryData = $this->updateCategoryAction->handle($category, $data);

        return response($categoryData);
    }

    /**
     * Delete category.
     *
     * @param  Category  $category
     *
     * @return Response
     */
    public function destroy(Category $category): Response
    {
        $this->deleteCategoryAction->handle($category);

        return response('', RespCode::HTTP_NO_CONTENT);
    }
}
