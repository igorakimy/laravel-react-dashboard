<?php

namespace App\Actions\Api\Category;

use App\Actions\Api\ApiAction;
use App\Data\Category\CategoryData;
use App\Data\Category\CategoryUpdateData;
use App\Models\Category;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Throwable;

final class UpdateCategory extends ApiAction
{
    /**
     * @throws Throwable
     */
    public function handle(Category $category, CategoryUpdateData $data): CategoryData
    {
        $category->fill($data->except('image')->toArray());

        if ($data->image) {

            $file = Storage::disk('temp')->path($data->image);

            $category->clearMediaCollection('image');
            $category->addMedia(new File($file))->toMediaCollection('image');

            Storage::disk('temp')->delete($data->image);
        }

        $category->save();

        return CategoryData::from($category->load(['parent', 'children']));
    }
}
