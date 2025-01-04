@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
    
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Budget Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-700">Total Income</h3>
                <button onclick="openIncomeModal()" 
                    class="bg-green-500 hover:bg-green-700 text-white text-sm font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline">
                    + Add Income
                </button>
            </div>
            <p class="text-2xl font-bold text-green-600 mt-2">LKR {{ number_format($totalIncome, 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Expenses</h3>
            <p class="text-2xl font-bold text-red-600">LKR {{ number_format($totalExpenses, 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Savings</h3>
            <p class="text-2xl font-bold text-blue-600">LKR {{ number_format($totalSavings, 2) }}</p>
        </div>
    </div>

    <!-- Create Budget Form -->
    <div class="mt-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Create Budget</h2>
            <form action="{{ route('budgets.store') }}" method="POST" id="budgetForm">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="category">
                        Category
                    </label>
                    <select name="category" id="category" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2 @error('category') border-red-500 @enderror"
                        onchange="toggleCustomCategory(this.value)">
                        <option value="">Select Category</option>
                        <option value="Utilities" {{ old('category') == 'Utilities' ? 'selected' : '' }}>Utilities</option>
                        <option value="Groceries" {{ old('category') == 'Groceries' ? 'selected' : '' }}>Groceries</option>
                        <option value="Transportation" {{ old('category') == 'Transportation' ? 'selected' : '' }}>Transportation</option>
                        <option value="Healthcare" {{ old('category') == 'Healthcare' ? 'selected' : '' }}>Healthcare</option>
                        <option value="Education" {{ old('category') == 'Education' ? 'selected' : '' }}>Education</option>
                        <option value="Communication" {{ old('category') == 'Communication' ? 'selected' : '' }}>Communication</option>
                        <option value="others" {{ old('category') == 'others' ? 'selected' : '' }}>Others</option>
                    </select>
                    @error('category')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                    
                    <input type="text" name="custom_category" id="custom_category" 
                        value="{{ old('custom_category') }}"
                        placeholder="Enter custom category"
                        style="display: none;"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('custom_category') border-red-500 @enderror">
                    @error('custom_category')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="amount">
                        Amount (Rs.)
                    </label>
                    <input type="number" step="0.01" name="amount" id="amount" required
                        value="{{ old('amount') }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('amount') border-red-500 @enderror">
                    <p id="suggestion" class="text-sm text-gray-600 mt-1 hidden">
                        Suggested amount: <span id="suggestedAmount"></span>
                    </p>
                    @error('amount')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Create Budget
                </button>
            </form>
        </div>
    </div>

    <!-- Budgets List -->
    <div class="mt-8">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold">Your Budgets</h2>
            </div>
            
            @if($budgets->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($budgets as $budget)
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold">{{ $budget->category_name }}</h3>
                                    <p class="text-gray-600">Budget: LKR {{ number_format($budget->amount, 2) }}</p>
                                    <p class="text-gray-600">Spent: LKR {{ number_format($budget->amount_spent, 2) }}</p>
                                </div>
                                <div class="flex space-x-2">
                                    <button onclick="openExpenseModal({{ $budget->id }})"
                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                        Add Expense
                                    </button>
                                    <form action="{{ route('budgets.destroy', $budget) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this budget?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mb-2">
                                <div class="bg-blue-600 h-2.5 rounded-full" 
                                     style="width: {{ min(($budget->amount_spent / $budget->amount) * 100, 100) }}%">
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 mb-4">
                                {{ number_format(($budget->amount_spent / $budget->amount) * 100, 1) }}% of budget used
                            </p>

                            <!-- Expenses List -->
                            @if($budget->expenses->count() > 0)
                                <div class="mt-4">
                                    <h4 class="text-md font-semibold mb-2">Expenses</h4>
                                    <div class="space-y-2">
                                        @foreach($budget->expenses as $expense)
                                            <div class="flex items-center justify-between bg-gray-50 p-3 rounded">
                                                <div>
                                                    <p class="font-medium">{{ $expense->expense_name }}</p>
                                                    <p class="text-sm text-gray-600">
                                                        LKR {{ number_format($expense->amount, 2) }} - 
                                                        {{ $expense->date->format('M d, Y') }}
                                                    </p>
                                                </div>
                                                <form action="{{ route('budgets.expenses.destroy', $expense) }}" 
                                                      method="POST" 
                                                      class="inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this expense?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="text-red-600 hover:text-red-800">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-6 text-center text-gray-500">
                    No budgets created yet.
                </div>
            @endif
        </div>
    </div>

    <!-- Add Expense Modal -->
    <div id="expenseModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 50;">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Add Expense</h3>
                <form action="{{ route('budgets.add-expense') }}" method="POST">
                    @csrf
                    <input type="hidden" name="budget_id" id="modal_budget_id">
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="expense_name">
                            Expense Description
                        </label>
                        <input type="text" name="expense_name" id="expense_name" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="expense_amount">
                            Amount (Rs.)
                        </label>
                        <input type="number" step="0.01" name="amount" id="expense_amount" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="closeExpenseModal()"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Cancel
                        </button>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Add Expense
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Income Modal -->
    <div id="incomeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 50;">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Add Income</h3>
                <form action="{{ route('incomes.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="income_amount">
                            Amount (Rs.)
                        </label>
                        <input type="number" step="0.01" name="amount" id="income_amount" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="closeIncomeModal()"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Cancel
                        </button>
                        <button type="submit"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Add Income
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    // Store suggested amounts from PHP
    const suggestedAmounts = @json($suggestedAmounts);
    console.log('Available budget suggestions:', suggestedAmounts);

    function toggleCustomCategory(value) {
        const customCategoryInput = document.getElementById('custom_category');
        const amountInput = document.getElementById('amount');
        const suggestionElement = document.getElementById('suggestion');
        const suggestedAmountElement = document.getElementById('suggestedAmount');

        console.log('Selected category:', value);
        
        if (value === 'others') {
            customCategoryInput.style.display = 'block';
            customCategoryInput.required = true;
            suggestionElement.classList.add('hidden');
            amountInput.value = '';
        } else {
            customCategoryInput.style.display = 'none';
            customCategoryInput.required = false;
            customCategoryInput.value = '';

            if (suggestedAmounts && suggestedAmounts[value]) {
                const suggestedAmount = suggestedAmounts[value];
                console.log('Found suggestion:', suggestedAmount);
                
                suggestedAmountElement.textContent = 'LKR ' + Number(suggestedAmount).toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                suggestionElement.classList.remove('hidden');
                amountInput.value = suggestedAmount;
            } else {
                console.log('No suggestion found for category:', value);
                suggestionElement.classList.add('hidden');
                amountInput.value = '';
            }
        }
    }

    // Set initial state based on selected category
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('category');
        if (categorySelect.value) {
            toggleCustomCategory(categorySelect.value);
        }

        document.getElementById('budgetForm').addEventListener('submit', function(e) {
            const category = document.getElementById('category').value;
            const customCategory = document.getElementById('custom_category').value;
            
            if (category === 'others' && !customCategory.trim()) {
                e.preventDefault();
                alert('Please enter a custom category name');
                document.getElementById('custom_category').focus();
            }
        });
    });

    function openExpenseModal(budgetId) {
        document.getElementById('modal_budget_id').value = budgetId;
        document.getElementById('expenseModal').classList.remove('hidden');
    }

    function closeExpenseModal() {
        document.getElementById('expenseModal').classList.add('hidden');
        document.getElementById('expense_name').value = '';
        document.getElementById('expense_amount').value = '';
    }

    function openIncomeModal() {
        document.getElementById('incomeModal').classList.remove('hidden');
    }

    function closeIncomeModal() {
        document.getElementById('incomeModal').classList.add('hidden');
        document.getElementById('income_amount').value = '';
    }

    // Close modal when clicking outside
    document.getElementById('expenseModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeExpenseModal();
        }
    });

    document.getElementById('incomeModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeIncomeModal();
        }
    });
    </script>
@endsection
