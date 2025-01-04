<?php

namespace Database\Factories;

use App\Models\Budget;
use App\Models\UserInfo;
use Illuminate\Database\Eloquent\Factories\Factory;

class BudgetFactory extends Factory
{
    protected $model = Budget::class;

    public function definition()
    {
        return [
            'user_id' => UserInfo::factory(),
            'category_name' => $this->faker->word,
            'amount' => $this->faker->randomFloat(2, 100, 10000),
            'amount_spent' => $this->faker->randomFloat(2, 0, 1000),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
