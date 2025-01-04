<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\UserInfo;
use App\Models\Budget;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition()
    {
        return [
            'user_id' => UserInfo::factory(),
            'budget_id' => Budget::factory(),
            'expense_name' => $this->faker->word,
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'date' => $this->faker->date(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
