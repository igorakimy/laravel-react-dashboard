<?php

namespace App\Actions\Api\Category;

use App\Data\Category\CategoryData;
use App\Models\Category;
use Spatie\LaravelData\Data;

final class ShowCategory extends Data
{
    public function handle(Category|int $category): CategoryData
    {
        if ( ! $category instanceof Category) {
            $category = Category::query()->findOrFail($category);
        }

        return CategoryData::from($category->load(['parent', 'children']))->include(
            'image',
            'parent',
            'children',
            'metas',
        );
    }
}
