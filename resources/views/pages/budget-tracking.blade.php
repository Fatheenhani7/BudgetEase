@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Budget Tracking</h1>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Income -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-700">Total Income</h2>
            <p class="text-2xl font-bold text-green-600">RM {{ number_format($totalIncome, 2) }}</p>
        </div>

        <!-- Total Expenses -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-700">Total Expenses</h2>
            <p class="text-2xl font-bold text-red-600">RM {{ number_format($totalExpenses, 2) }}</p>
        </div>

        <!-- Total Savings -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-700">Total Savings</h2>
            <p class="text-2xl font-bold text-blue-600">RM {{ number_format($totalSavings, 2) }}</p>
        </div>
    </div>

    <!-- Forms Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Add Income Form -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Add Income</h2>
            <form action="{{ route('income.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="income_amount">
                        Amount (RM)
                    </label>
                    <input type="number" step="0.01" name="amount" id="income_amount" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Add Income
                </button>
            </form>
        </div>

        <!-- Create Budget Form -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Create Budget</h2>
            <form action="{{ route('budgets.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="category">
                        Category
                    </label>
                    <input type="text" name="category" id="category" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="budget_amount">
                        Amount (RM)
                    </label>
                    <input type="number" step="0.01" name="amount" id="budget_amount" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <button type="submit"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Create Budget
                </button>
            </form>
        </div>

        <!-- Add Expense Form -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Add Expense</h2>
            <form action="{{ route('expenses.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="budget_id">
                        Budget Category
                    </label>
                    <select name="budget_id" id="budget_id" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Select a budget</option>
                        @foreach($budgets as $budget)
                            <option value="{{ $budget->id }}">{{ $budget->category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="expense_name">
                        Expense Name
                    </label>
                    <input type="text" name="expense_name" id="expense_name" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="expense_amount">
                        Amount (RM)
                    </label>
                    <input type="number" step="0.01" name="amount" id="expense_amount" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <button type="submit"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Add Expense
                </button>
            </form>
        </div>
    </div>

    <!-- Budget Overview -->
    <div class="mt-8">
        <h2 class="text-2xl font-bold mb-4">Budget Overview</h2>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Budget</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Spent</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remaining</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($budgets as $budget)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $budget->category }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">RM {{ number_format($budget->amount_budgeted, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">RM {{ number_format($budget->amount_spent, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">RM {{ number_format($budget->remaining_amount, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ min($budget->percentage_spent, 100) }}%"></div>
                            </div>
                            <span class="text-sm text-gray-600">{{ number_format($budget->percentage_spent, 1) }}%</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
