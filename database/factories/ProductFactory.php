<?php

namespace Database\Factories;

use App\Models\UserInfo;
use App\Models\MarketplaceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $categories = MarketplaceCategory::pluck('name')->toArray();
        $conditions = ['New', 'Like New', 'Good', 'Fair'];

        return [
            'seller_id' => UserInfo::factory(),
            'title' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 10, 1000),
            'category' => fake()->randomElement($categories),
            'condition_status' => fake()->randomElement($conditions),
            'location' => fake()->city()
        ];
    }
}
