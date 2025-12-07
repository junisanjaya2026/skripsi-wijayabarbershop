<?php

namespace Database\Factories;

use App\Models\Category;
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
            'category_id' => $this->faker->numberBetween(1,2),  
            'description' => $this->faker->sentence(),
            'image_path' => fake()->randomElement([
                'https://images.unsplash.com/photo-1503951914875-452162b0f3f1',
                'https://images.unsplash.com/photo-1647140655214-e4a2d914971f',
                'https://images.unsplash.com/photo-1542818279-04aa19d54f06',
            ]),
            'is_active' => $this->faker->boolean(80),  
        ];
    }
}
