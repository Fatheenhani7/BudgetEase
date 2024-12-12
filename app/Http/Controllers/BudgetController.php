<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Expense;
use App\Models\Income;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $budgets = $request->user()->budgets()->with('expenses')->get();
        return response()->json($budgets);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        $budget = $request->user()->budgets()->create($request->all());
        return response()->json($budget, 201);
    }

    public function show(Budget $budget)
    {
        $this->authorize('view', $budget);
        return response()->json($budget->load('expenses'));
    }

    public function update(Request $request, Budget $budget)
    {
        $this->authorize('update', $budget);
        
        $request->validate([
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        $budget->update($request->all());
        return response()->json($budget);
    }

    public function destroy(Budget $budget)
    {
        $this->authorize('delete', $budget);
        $budget->delete();
        return response()->json(null, 204);
    }

    public function dashboard(Request $request)
    {
        $user = $request->user();
        
        $totalIncome = $user->incomes()->sum('amount');
        $totalExpenses = $user->expenses()->sum('amount');
        $budgets = $user->budgets()->with('expenses')->get();
        
        $budgetSummary = $budgets->map(function ($budget) {
            $spent = $budget->expenses->sum('amount');
            return [
                'id' => $budget->id,
                'category' => $budget->category,
                'budget_amount' => $budget->amount,
                'spent' => $spent,
                'remaining' => $budget->amount - $spent,
                'percentage_used' => $budget->amount > 0 ? ($spent / $budget->amount) * 100 : 0
            ];
        });

        return response()->json([
            'total_income' => $totalIncome,
            'total_expenses' => $totalExpenses,
            'savings' => $totalIncome - $totalExpenses,
            'budget_summary' => $budgetSummary
        ]);
    }
}
