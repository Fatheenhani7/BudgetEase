<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $incomes = $request->user()->incomes()->get();
        return response()->json($incomes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'source' => 'nullable|string|max:255',
        ]);

        $income = $request->user()->incomes()->create($request->all());
        return response()->json($income, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Income $income)
    {
        $this->authorize('view', $income);
        return response()->json($income);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Income $income)
    {
        $this->authorize('update', $income);
        
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'source' => 'nullable|string|max:255',
        ]);

        $income->update($request->all());
        return response()->json($income);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Income $income)
    {
        $this->authorize('delete', $income);
        $income->delete();
        return response()->json(null, 204);
    }
}
