<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Income;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();

        // Get total income
        $totalIncome = Income::where('user_id', $user_id)->sum('amount');

        // Get total expenses
        $totalExpenses = Expense::where('user_id', $user_id)->sum('amount');

        // Calculate total savings
        $totalSavings = $totalIncome - $totalExpenses;

        // Get budgets with their expenses
        $budgets = Budget::where('user_id', $user_id)
            ->with(['expenses' => function($query) {
                $query->orderBy('date', 'desc');
            }])
            ->get();

        // Calculate suggested amounts based on income level
        $suggestedAmounts = $this->calculateSuggestedAmounts($totalIncome);

        // Debug output
        Log::info('Budget Index Data:', [
            'Total Income' => $totalIncome,
            'Income Bracket' => $totalIncome <= 50000 ? 'Low' : ($totalIncome <= 150000 ? 'Medium' : 'High'),
            'Suggested Amounts' => $suggestedAmounts
        ]);

        return view('budgets.index', compact(
            'totalIncome',
            'totalExpenses',
            'totalSavings',
            'budgets',
            'suggestedAmounts'
        ));
    }

    private function calculateSuggestedAmounts($totalIncome)
    {
        // Define income brackets and their corresponding percentages
        $brackets = [
            'low' => ['limit' => 50000, 'percentages' => [
                'Utilities' => 15,
                'Groceries' => 25,
                'Transportation' => 15,
                'Healthcare' => 10,
                'Education' => 15,
                'Communication' => 10
            ]],
            'medium' => ['limit' => 150000, 'percentages' => [
                'Utilities' => 12,
                'Groceries' => 20,
                'Transportation' => 12,
                'Healthcare' => 8,
                'Education' => 12,
                'Communication' => 8
            ]],
            'high' => ['limit' => PHP_FLOAT_MAX, 'percentages' => [
                'Utilities' => 8,
                'Groceries' => 15,
                'Transportation' => 8,
                'Healthcare' => 6,
                'Education' => 8,
                'Communication' => 6
            ]]
        ];

        // Determine which bracket the income falls into
        $selectedBracket = $brackets['low']['percentages'];
        foreach ($brackets as $bracket) {
            if ($totalIncome <= $bracket['limit']) {
                $selectedBracket = $bracket['percentages'];
                break;
            }
        }

        // Calculate suggested amounts with maximum caps
        $suggestedAmounts = [];
        foreach ($selectedBracket as $category => $percentage) {
            $suggestedAmount = round(($totalIncome * $percentage) / 100, 2);
            
            // Apply maximum caps based on category
            $maxAmounts = [
                'Utilities' => 25000,
                'Groceries' => 40000,
                'Transportation' => 25000,
                'Healthcare' => 20000,
                'Education' => 30000,
                'Communication' => 15000
            ];

            // Cap the suggested amount at the maximum
            $suggestedAmounts[$category] = min($suggestedAmount, $maxAmounts[$category]);
        }

        return $suggestedAmounts;
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'category' => 'required|string|max:100',
                'custom_category' => 'nullable|string|max:100',
                'amount' => 'required|numeric|min:0'
            ]);

            $category = $request->category === 'others' ? $request->custom_category : $request->category;
            
            if (empty($category)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Please provide a valid category');
            }

            Budget::create([
                'user_id' => Auth::id(),
                'category_name' => $category,
                'amount' => $request->amount,
                'amount_spent' => 0
            ]);

            return redirect()->back()->with('success', 'Budget created successfully');
        } catch (\Exception $e) {
            Log::error('Budget creation error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create budget. Please try again.');
        }
    }

    public function addExpense(Request $request)
    {
        $request->validate([
            'budget_id' => 'required|exists:budgets,id',
            'expense_name' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0'
        ]);

        $budget = Budget::findOrFail($request->budget_id);
        
        // Check if the budget belongs to the current user
        if ($budget->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        DB::beginTransaction();
        try {
            // Create expense
            Expense::create([
                'user_id' => Auth::id(),
                'budget_id' => $request->budget_id,
                'expense_name' => $request->expense_name,
                'amount' => $request->amount,
                'date' => now()
            ]);

            // Update budget spent amount
            $budget->amount_spent = $budget->amount_spent + $request->amount;
            $budget->save();

            DB::commit();
            return redirect()->back()->with('success', 'Expense added successfully');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error adding expense: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add expense. Please try again.');
        }
    }

    public function addIncome(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0'
        ]);

        Income::create([
            'user_id' => Auth::id(),
            'amount' => $request->amount
        ]);

        return redirect()->back()->with('success', 'Income added successfully');
    }

    /**
     * Show the form for creating a new budget.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = [
            'Utilities' => 'Utilities',
            'Groceries' => 'Groceries',
            'Transportation' => 'Transportation',
            'Healthcare' => 'Healthcare',
            'Education' => 'Education',
            'Communication' => 'Communication',
            'others' => 'Other (Custom)'
        ];

        // Get total income for the user
        $totalIncome = Income::where('user_id', Auth::id())->sum('amount') ?? 0;

        // Debug total income
        Log::info('Total Income:', ['amount' => $totalIncome]);

        // Define suggested percentages for each category
        $suggestedPercentages = [
            'Utilities' => 15,
            'Groceries' => 25,
            'Transportation' => 15,
            'Healthcare' => 10,
            'Education' => 15,
            'Communication' => 10
        ];

        // Calculate suggested amounts
        $suggestedAmounts = [];
        if ($totalIncome > 0) {
            foreach ($suggestedPercentages as $category => $percentage) {
                $suggestedAmounts[$category] = round(($totalIncome * $percentage) / 100, 2);
            }
        }

        // Debug output
        Log::info('Budget Creation Data:', [
            'Total Income' => $totalIncome,
            'Suggested Amounts' => $suggestedAmounts,
            'Categories' => $categories
        ]);

        return view('budgets.create', compact('categories', 'suggestedAmounts', 'totalIncome'));
    }

    public function edit(Budget $budget)
    {
        // Check if the budget belongs to the current user
        if ($budget->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        return view('budgets.edit', compact('budget'));
    }

    public function update(Request $request, Budget $budget)
    {
        // Check if the budget belongs to the current user
        if ($budget->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0'
        ]);

        $budget->update([
            'category_name' => $request->category,
            'amount' => $request->amount
        ]);

        return redirect()->route('budgets.index')->with('success', 'Budget updated successfully');
    }

    public function destroy(Budget $budget)
    {
        // Check if the budget belongs to the current user
        if ($budget->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $budget->delete();
        return redirect()->route('budgets.index')->with('success', 'Budget deleted successfully');
    }

    public function editExpense(Expense $expense)
    {
        // Check if the expense belongs to the current user
        if ($expense->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $budgets = Budget::where('user_id', Auth::id())->get();
        return view('expenses.edit', compact('expense', 'budgets'));
    }

    public function updateExpense(Request $request, Expense $expense)
    {
        // Check if the expense belongs to the current user
        if ($expense->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'expense_name' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
            'budget_id' => 'required|exists:budgets,id'
        ]);

        $budget = Budget::findOrFail($request->budget_id);
        if ($budget->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        DB::beginTransaction();
        try {
            // Update the old budget's spent amount
            $oldBudget = Budget::findOrFail($expense->budget_id);
            $oldBudget->amount_spent -= $expense->amount;
            $oldBudget->save();

            // Update the expense
            $expense->update([
                'expense_name' => $request->expense_name,
                'amount' => $request->amount,
                'budget_id' => $request->budget_id
            ]);

            // Update the new budget's spent amount
            $budget->amount_spent += $request->amount;
            $budget->save();

            DB::commit();
            return redirect()->route('budgets.index')->with('success', 'Expense updated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to update expense: ' . $e->getMessage());
        }
    }

    public function destroyExpense(Expense $expense)
    {
        try {
            // Check if the expense belongs to the current user
            if ($expense->user_id !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }

            $budget = $expense->budget;
            
            DB::beginTransaction();
            
            // Update budget amount_spent
            $budget->amount_spent = $budget->amount_spent - $expense->amount;
            $budget->save();
            
            // Delete the expense
            $expense->delete();
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Expense deleted successfully');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting expense: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete expense. Please try again.');
        }
    }

    public function show(Budget $budget)
    {
        if ($budget->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('budgets.show', compact('budget'));
    }
}
