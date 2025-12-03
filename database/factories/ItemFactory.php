<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'item_name' => $this->faker->word(),
            'price' => $this->faker->numberBetween(1000, 100000),
            'category_id' => \App\Models\Category::factory(),  
            'description' => $this->faker->sentence(),
            'image_path' => $this->faker->imageUrl(),
            'is_active' => $this->faker->boolean(80),  
        ];
    }
}
