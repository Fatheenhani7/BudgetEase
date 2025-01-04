<?php

namespace Tests\Feature\Budget;

use App\Models\UserInfo;
use App\Models\Budget;
use App\Models\Expense;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BudgetManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = UserInfo::factory()->create();
    }

    public function test_user_can_create_budget()
    {
        $this->actingAs($this->user);

        $budgetData = [
            'category' => 'Groceries',
            'amount' => 1000.00,
        ];

        $response = $this->post(route('budgets.store'), $budgetData);

        $response->assertRedirect();
        $this->assertDatabaseHas('budgets', [
            'user_id' => $this->user->id,
            'category_name' => 'Groceries',
            'amount' => 1000.00,
            'amount_spent' => 0.00
        ]);
    }

    public function test_user_can_view_their_budgets()
    {
        $this->actingAs($this->user);

        $budget = Budget::factory()->create([
            'user_id' => $this->user->id,
            'category_name' => 'Test Budget',
            'amount' => 500.00,
            'amount_spent' => 0.00
        ]);

        $response = $this->get(route('budgets.index'));

        $response->assertStatus(200);
        $response->assertSee('Test Budget');
        $response->assertSee('500.00');
    }

    public function test_user_can_update_budget()
    {
        $this->actingAs($this->user);

        $budget = Budget::factory()->create([
            'user_id' => $this->user->id,
            'category_name' => 'Old Category',
            'amount' => 500.00,
            'amount_spent' => 0.00
        ]);

        $response = $this->put(route('budgets.update', $budget), [
            'category' => 'New Category',
            'amount' => 1000.00
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('budgets', [
            'id' => $budget->id,
            'category_name' => 'New Category',
            'amount' => 1000.00
        ]);
    }

    public function test_user_cannot_view_others_budgets()
    {
        $this->actingAs($this->user);
        
        $otherUser = UserInfo::factory()->create();
        $otherBudget = Budget::factory()->create([
            'user_id' => $otherUser->id,
            'category_name' => 'Other Budget',
            'amount' => 500.00,
            'amount_spent' => 0.00
        ]);

        $response = $this->get(route('budgets.show', $otherBudget));
        $response->assertStatus(403);
    }

    public function test_budget_amount_spent_updates_correctly()
    {
        $this->actingAs($this->user);

        $budget = Budget::factory()->create([
            'user_id' => $this->user->id,
            'category_name' => 'Test Budget',
            'amount' => 1000.00,
            'amount_spent' => 0.00
        ]);

        // Add an expense to this budget
        $expenseData = [
            'budget_id' => $budget->id,
            'expense_name' => 'Test Expense',
            'amount' => 300.00
        ];

        $this->post(route('budgets.add-expense'), $expenseData);

        $this->assertDatabaseHas('budgets', [
            'id' => $budget->id,
            'amount_spent' => 300.00
        ]);
    }
}
