<?php

namespace App\Data\Category;

use App\Data\Meta\MetaData;
use App\Models\Category;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

final class CategoryData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public string|null $description,

        public CategoryData|Lazy|null $parent,

        #[DataCollectionOf(CategoryData::class)]
        public DataCollection|Lazy|null $children,

        #[DataCollectionOf(MetaData::class)]
        public DataCollection|Lazy|null $metas,

        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public ?Carbon $created_at,

        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public ?Carbon $updated_at,
    ) {
    }

    public static function fromModel(Category $category): self
    {
        return new self(
            id: $category->id,
            name: $category->name,
            slug: $category->slug,
            description: $category->description,
            parent: Lazy::whenLoaded(
                'parent',
                $category,
                fn() => $category->parent ? CategoryData::from($category->parent)->include('children') : null
            ),
            children: Lazy::whenLoaded(
                'children',
                $category,
                fn() => CategoryData::collection($category->children)->include('children')
            ),
            metas: Lazy::create(fn() => MetaData::collection($category->metas)),
            created_at: $category->created_at,
            updated_at: $category->updated_at
        );
    }
}
