<?php

namespace App\Data\Category;

use Spatie\LaravelData\Data;

final class CategoryData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public string|null $description,
        public CategoryData|null $parent,
    ) {
    }
}
