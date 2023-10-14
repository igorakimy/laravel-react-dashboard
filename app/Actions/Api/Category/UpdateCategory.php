<?php

namespace App\Actions\Api\Category;

use App\Actions\Api\ApiAction;
use App\Data\Category\CategoryData;
use App\Data\Category\CategoryUpdateData;
use App\Models\Category;
use Throwable;

final class UpdateCategory extends ApiAction
{
    /**
     * @throws Throwable
     */
    public function handle(Category $category, CategoryUpdateData $data): CategoryData
    {
        $category->fill($data->toArray());

        $category->save();

        return CategoryData::from($category->load(['parent', 'children']));
    }
}
