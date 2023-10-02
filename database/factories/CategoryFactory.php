<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->words(rand(2, 3), true);

        return [
            'name' => $name,
            'description' => $this->faker->sentence(),
            'parent_id' => $this->faker->randomElement([
                Category::factory(),
                null,
            ]),
        ];
    }
}
