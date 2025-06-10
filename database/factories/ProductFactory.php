<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
        //   'name',
        // 'description',
        // 'price',
        // 'image',
        // 'is_featured',
        // 'category_id',
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $randomImages =[
        'https://m.media-amazon.com/images/I/41WpqIvJWRL._AC_UY436_QL65_.jpg',
        'https://m.media-amazon.com/images/I/61ghDjhS8vL._AC_UY436_QL65_.jpg',
        'https://m.media-amazon.com/images/I/61c1QC4lF-L._AC_UY436_QL65_.jpg',
        'https://m.media-amazon.com/images/I/710VzyXGVsL._AC_UY436_QL65_.jpg',
        'https://m.media-amazon.com/images/I/61EPT-oMLrL._AC_UY436_QL65_.jpg',
        'https://m.media-amazon.com/images/I/71r3ktfakgL._AC_UY436_QL65_.jpg',
        'https://m.media-amazon.com/images/I/61CqYq+xwNL._AC_UL640_QL65_.jpg',
        'https://m.media-amazon.com/images/I/71cVOgvystL._AC_UL640_QL65_.jpg',
        'https://m.media-amazon.com/images/I/71E+oh38ZqL._AC_UL640_QL65_.jpg',
        'https://m.media-amazon.com/images/I/61uSHBgUGhL._AC_UL640_QL65_.jpg',
        'https://m.media-amazon.com/images/I/71nDK2Q8HAL._AC_UL640_QL65_.jpg'
   ];
        return [
            'name' => fake()->name(),
            'description' => fake()->sentence(5),
            'price' => fake()->randomElement(['10' , '100']), // Random price between 10 and 1000
            'image' => $randomImages[rand(0, 10)],
            'rating' => fake()->randomFloat(1, 1, 5), // Random rating between 1 and 5
            'is_featured' => fake()->randomElement([true, false]),
            'category_id' => fake()->randomNumber(), // Assuming categories are pre-populated with IDs from 1 to 10

        ];
    }
}
