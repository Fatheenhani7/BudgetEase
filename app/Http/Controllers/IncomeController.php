<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $incomes = $request->user()->incomes;
        return view('incomes.index', compact('incomes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('incomes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric|min:0'
            ]);

            Income::create([
                'user_id' => Auth::id(),
                'amount' => $request->amount,
                'date' => now()
            ]);

            return redirect()->back()->with('success', 'Income added successfully');
        } catch (\Exception $e) {
            Log::error('Income creation error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to add income. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Income $income)
    {
        $this->authorize('view', $income);
        return view('incomes.show', compact('income'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Income $income)
    {
        if ($request->user()->id !== $income->user_id) {
            abort(403);
        }
        return view('incomes.edit', compact('income'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Income $income)
    {
        if ($request->user()->id !== $income->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string',
            'date' => 'required|date'
        ]);

        $income->update($validated);

        return redirect()->back()->with('success', 'Income updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Income $income)
    {
        if ($request->user()->id !== $income->user_id) {
            abort(403);
        }
        $income->delete();
        return redirect()->back()->with('success', 'Income deleted successfully');
    }
}
