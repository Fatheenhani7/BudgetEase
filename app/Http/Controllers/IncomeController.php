<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;
<<<<<<< HEAD
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
=======
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
<<<<<<< HEAD
        $incomes = $request->user()->incomes;
        return view('incomes.index', compact('incomes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('incomes.create');
=======
        $incomes = $request->user()->incomes()->get();
        return response()->json($incomes);
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
<<<<<<< HEAD
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
=======
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'source' => 'nullable|string|max:255',
        ]);

        $income = $request->user()->incomes()->create($request->all());
        return response()->json($income, 201);
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
    }

    /**
     * Display the specified resource.
     */
    public function show(Income $income)
    {
        $this->authorize('view', $income);
<<<<<<< HEAD
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
=======
        return response()->json($income);
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Income $income)
    {
<<<<<<< HEAD
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
=======
        $this->authorize('update', $income);
        
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'source' => 'nullable|string|max:255',
        ]);

        $income->update($request->all());
        return response()->json($income);
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
    }

    /**
     * Remove the specified resource from storage.
     */
<<<<<<< HEAD
    public function destroy(Request $request, Income $income)
    {
        if ($request->user()->id !== $income->user_id) {
            abort(403);
        }
        $income->delete();
        return redirect()->back()->with('success', 'Income deleted successfully');
=======
    public function destroy(Income $income)
    {
        $this->authorize('delete', $income);
        $income->delete();
        return response()->json(null, 204);
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
    }
}
