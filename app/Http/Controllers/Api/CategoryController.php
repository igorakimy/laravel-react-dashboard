<?php

namespace App\Http\Controllers\Api;

use App\Data\Category\CategoryData;
use App\Http\Controllers\ApiController;
use App\Models\Category;
use Illuminate\Http\Request;

final class CategoryController extends ApiController
{
    public function index()
    {
        $categories = Category::query()->get();

        return CategoryData::collection($categories);
    }

    public function show(Request $request, Category $category)
    {

    }

    public function store(Request $request)
    {

    }

    public function update(Request $request, Category $category)
    {

    }

    public function destroy(Category $category)
    {

    }
}
