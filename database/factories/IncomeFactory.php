<?php

namespace Database\Factories;

use App\Models\Income;
use App\Models\UserInfo;
use Illuminate\Database\Eloquent\Factories\Factory;

class IncomeFactory extends Factory
{
    protected $model = Income::class;

    public function definition()
    {
        return [
            'user_id' => UserInfo::factory(),
            'description' => $this->faker->sentence,
            'amount' => $this->faker->randomFloat(2, 1000, 10000),
            'category' => $this->faker->randomElement(['salary', 'freelance', 'investment', 'other']),
            'date' => $this->faker->date(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
