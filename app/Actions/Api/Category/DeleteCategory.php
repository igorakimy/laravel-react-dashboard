<?php

namespace App\Actions\Api\Category;

use App\Data\Category\CategoryData;
use App\Models\Category;
use Spatie\LaravelData\Data;

final class DeleteCategory extends Data
{
    public function handle(Category|int $category): CategoryData
    {
        if ( ! $category instanceof Category) {
            $category = Category::query()->findOrFail($category);
        }

        $category?->delete();

        return CategoryData::from($category);
    }
}
