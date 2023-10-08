<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Color;
use App\Models\Material;
use App\Models\Product;
use App\Models\Type;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(rand(1, 3), true),
            'sku' => $this->faker->unique()->uuid(),
            'quantity' => $this->faker->randomDigit(),
            'width' => $this->faker->randomFloat(2, 10, 1000),
            'height' => $this->faker->randomFloat(2, 10, 1000),
            'weight' => $this->faker->randomFloat(2, 10, 1000),
            'cost_price' => $this->faker->randomFloat(2, 10, 9999),
            'selling_price' => $this->faker->randomFloat(2, 10, 9999),
            'margin' => $this->faker->randomFloat(2, 10, 100),
            'barcode' => (string)$this->faker->randomNumber(9, true),
            'location' => $this->faker->address(),
            'color_id' => Color::query()->inRandomOrder()->first()?->id,
            'material_id' => Material::query()->inRandomOrder()->first()?->id,
            'vendor_id' => Vendor::query()->inRandomOrder()->first()?->id,
            'type_id' => Type::query()->inRandomOrder()->first()?->id,
            'caption' => $this->faker->sentence(),
            'description' => $this->faker->text(rand(200, 1000)),
        ];
    }
}
