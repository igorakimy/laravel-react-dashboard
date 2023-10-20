<?php

namespace App\Actions\Api\Category;

use App\Actions\Api\ApiAction;
use App\Data\Category\CategoryData;
use App\Models\Category;
use Spatie\LaravelData\DataCollection;

final class FetchCategoriesForTree extends ApiAction
{
    public function handle(): DataCollection {

        $categories = Category::query()
                              ->with(['parent', 'children'])
                              ->whereNull('parent_id')
                              ->get();

        return CategoryData::collection($categories)
                           ->include('parent', 'children');
    }
}
