<?php

namespace App\Jobs;

use App\Models\Category;
use App\Models\Color;
use App\Models\Material;
use App\Models\Model;
use App\Models\Product;
use App\Models\Type;
use App\Models\Vendor;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class ImportProductsCsv implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected array $relations = [
        'color_id'    => Color::class,
        'material_id' => Material::class,
        'vendor_id'   => Vendor::class,
        'type_id'     => Type::class,
    ];

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Collection $products,
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->batch()->cancelled()) {
            return;
        }

        $products = $this->products->map(function ($item) {
            $row = [];
            $keys = array_keys($item);

            foreach ($keys as $key) {
                if (in_array($key, array_keys($this->relations))) {
                    if ($item[$key] === null) {
                        $row[$key] = null;
                    }

                    /** @var Model $modelClass */
                    $modelClass = $this->relations[$key];
                    $model = $modelClass::query()->select(['id', 'name'])
                                        ->where('name', $item[$key])
                                        ->first();

                    $row[$key] = $model?->id;
                } elseif ($key === 'categories') {
                    $categoriesIds = Category::query()->select(['id', 'name'])
                                             ->whereIn('name', explode(',', $item[$key]))
                                             ->pluck('id')
                                             ->toArray();

                    $row['categories'] = $categoriesIds;
                } else {
                    $row[$key] = $item[$key];
                }
            }
            return $row;
        });

        foreach ($products as $rowProduct) {
            $categoriesIds = [];
            if (isset($rowProduct['categories'])) {
                $categoriesIds = $rowProduct['categories'];
                unset($rowProduct['categories']);
            }

            $product = Product::query()->updateOrCreate(
                ['sku' => $rowProduct['sku']],
                $rowProduct,
            );

            $product->categories()->sync($categoriesIds);
        }
    }
}
