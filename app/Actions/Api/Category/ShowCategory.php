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
            $category = Category::query()
                        ->with(['parent', 'children'])
                        ->findOrFail($category);
        }

        $category->load(['parent', 'children']);

        return CategoryData::from($category);
    }
}
