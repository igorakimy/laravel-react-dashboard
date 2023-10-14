<?php

namespace App\Actions\Api\Category;

use App\Actions\Api\ApiAction;
use App\Data\Category\CategoryData;
use App\Data\Category\CategoryStoreData;
use App\Models\Category;
use Throwable;

final class StoreCategory extends ApiAction
{
    /**
     * @throws Throwable
     */
    public function handle(CategoryStoreData $data): CategoryData
    {
        $category = new Category();

        $category->fill($data->toArray());

        $category->save();

        return CategoryData::from($category->load(['parent', 'children']));
    }
}
