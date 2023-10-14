<?php

namespace App\Actions\Api\Category;

use App\Actions\Api\ApiAction;
use App\Data\Category\CategoryData;
use App\Models\Category;
use Spatie\LaravelData\DataCollection;

final class FetchCategoriesForSelect extends ApiAction
{
    public function handle(): DataCollection {

        $categories = Category::query()
                              ->with(['parent', 'children'])
                              ->get();

        return CategoryData::collection($categories);
    }
}
