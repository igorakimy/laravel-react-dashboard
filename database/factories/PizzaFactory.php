<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pizza>
 */
class PizzaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'ingredients' => $this->faker->text(),
            'price' => $this->faker->randomFloat(2, 1, 100),
            'photo_name' => $this->faker->randomElement([
                'pizzas/pizza1.jpg',
                'pizzas/pizza2.jpg',
                'pizzas/pizza3.jpg',
            ]),
            'sold_out' => $this->faker->boolean(),
        ];
    }
}
