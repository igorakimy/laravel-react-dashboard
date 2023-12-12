<?php

namespace App\Actions\Api\Product;

use App\Actions\Api\ApiAction;
use App\Models\Product;
use Illuminate\Support\Collection;

final class FetchProducts extends ApiAction
{
    public function handle(array $columns = []): Collection
    {
        return Product::query()->get($columns);
    }
}
