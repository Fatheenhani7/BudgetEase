<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
<<<<<<< HEAD
        $expenses = $request->user()->expenses;
        return view('expenses.index', compact('expenses'));
=======
        $expenses = $request->user()->expenses()->with('budget')->get();
        return response()->json($expenses);
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
    }

    public function store(Request $request)
    {
<<<<<<< HEAD
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
=======
        $request->validate([
            'budget_id' => 'required|exists:budgets,id',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        $expense = $request->user()->expenses()->create($request->all());
        return response()->json($expense, 201);
    }

    public function show(Expense $expense)
    {
        $this->authorize('view', $expense);
        return response()->json($expense->load('budget'));
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
    }

    public function update(Request $request, Expense $expense)
    {
<<<<<<< HEAD
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
=======
        $this->authorize('update', $expense);
        
        $request->validate([
            'budget_id' => 'required|exists:budgets,id',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        $expense->update($request->all());
        return response()->json($expense);
    }

    public function destroy(Expense $expense)
    {
        $this->authorize('delete', $expense);
        $expense->delete();
        return response()->json(null, 204);
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
    }
}
