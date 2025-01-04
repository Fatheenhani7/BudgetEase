<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $expenses = $request->user()->expenses;
        return view('expenses.index', compact('expenses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string',
            'budget_id' => 'required|exists:budgets,id',
            'date' => 'required|date'
        ]);

        $expense = $request->user()->expenses()->create($validated);

        return redirect()->back()->with('success', 'Expense added successfully');
    }

    public function edit(Request $request, Expense $expense)
    {
        if ($request->user()->id !== $expense->user_id) {
            abort(403);
        }

        return view('expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        if ($request->user()->id !== $expense->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string',
            'budget_id' => 'required|exists:budgets,id',
            'date' => 'required|date'
        ]);

        $expense->update($validated);

        return redirect()->back()->with('success', 'Expense updated successfully');
    }

    public function destroy(Request $request, Expense $expense)
    {
        if ($request->user()->id !== $expense->user_id) {
            abort(403);
        }

        $expense->delete();
        return redirect()->back()->with('success', 'Expense deleted successfully');
    }
}
