<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if ( ! app()->isProduction()) {
            for($i = 0; $i < 1000; $i++) {
                $product = Product::factory(1)->create()->first();

                $categoriesIDs = Category::query()
                                         ->inRandomOrder()
                                         ->limit(rand(1, 3))
                                         ->pluck('id');

                $product->categories()->attach($categoriesIDs);
            }
        }
    }
}
