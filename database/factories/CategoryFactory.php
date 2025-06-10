<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
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
        return [
            'name' => fake()->randomElement([
                'Food',
                'Electronics',
                'Clothing',
                'Books',
                'Toys',
                'Furniture',
                'Beauty',
                'Sports',
                'Automotive',
                'Health',
            ]),

            'image' => fake()->randomElement([
                'images/food.png',     // Technology
                'imageshealth.jpg',     // Health
                'images/education.jpg', // Education
                   // Business
            ]),
            'description' => fake()->sentence(),
        ];
    }
}
