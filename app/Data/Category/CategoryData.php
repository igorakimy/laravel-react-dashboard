<?php

namespace App\Data\Category;

use App\Models\Category;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

final class CategoryData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public string|null $description,

        public int|null $parent,

        #[DataCollectionOf(CategoryData::class)]
        public DataCollection|null $children,

        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public ?Carbon $created_at,

        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public ?Carbon $updated_at,
    ) {
    }

    public static function fromModel(Category $category): self
    {
        $category->load(['parent', 'children']);

        return new self(
            id: $category->id,
            name: $category->name,
            slug: $category->slug,
            description: $category->description,
            parent: $category->parent?->id,
            children: CategoryData::collection($category->children),
            created_at: $category->created_at,
            updated_at: $category->updated_at
        );
    }
}
