<?php

namespace App\Data\Category;

use Exception;
use Illuminate\Http\Request;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\References\RouteParameterReference;

final class CategoryUpdateData extends Data
{
    public function __construct(
        public string $name,
        public int|null $parent_id,
        public string|null $description
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        $request->validate(self::rules());

        return new self(
            name: $request->input('name'),
            parent_id: $request->input('parent'),
            description: $request->input('description'),
        );
    }

    /**
     * @throws Exception
     */
    public static function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                new Unique(
                    table: 'categories',
                    column: 'name',
                    ignore: new RouteParameterReference('category', 'id')
                )
            ],
            'parent' => ['nullable', 'integer'],
            'description' => ['nullable', 'string'],
        ];
    }
}
