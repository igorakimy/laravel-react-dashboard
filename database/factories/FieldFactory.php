<?php

namespace Database\Factories;

use App\Enums\FieldType;
use App\Models\Field;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Field>
 */
class FieldFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(rand(1, 2), true),
            'type' => $this->faker->randomElement(FieldType::cases()),
            'description' => $this->faker->sentence(),
        ];
    }
}
