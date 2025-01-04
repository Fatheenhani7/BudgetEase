<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get recent transactions
        $recentTransactions = collect();
        
        $expenses = Expense::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($expense) {
                return [
                    'type' => 'expense',
                    'description' => $expense->description,
                    'amount' => $expense->amount,
                    'date' => $expense->created_at,
                ];
            });
            
        $incomes = Income::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($income) {
                return [
                    'type' => 'income',
                    'description' => $income->description,
                    'amount' => $income->amount,
                    'date' => $income->created_at,
                ];
            });
            
        $recentTransactions = $expenses->merge($incomes)
            ->sortByDesc('date')
            ->take(5);

        // Get marketplace items
        $marketplaceItems = Product::latest()
            ->take(4)
            ->get();

        // Get recent products
        $recentProducts = Product::with(['primaryImage', 'seller'])
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // Calculate budget overview
        $totalBudget = Budget::where('user_id', $user->id)->sum('amount');
        $totalExpenses = Expense::where('user_id', $user->id)->sum('amount');
        $remainingAmount = $totalBudget - $totalExpenses;
        $spentAmount = $totalExpenses;

        return view('pages.home', compact(
            'recentTransactions',
            'marketplaceItems',
            'spentAmount',
            'remainingAmount',
            'recentProducts'
        ));
    }
}
