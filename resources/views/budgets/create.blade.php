@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold">Create New Budget</h2>
            @if($totalIncome > 0)
                <p class="text-sm text-gray-600 mt-2">Based on your total income of LKR {{ number_format($totalIncome, 2) }}</p>
            @endif
        </div>
        
        <div class="p-6">
            <form action="{{ route('budgets.store') }}" method="POST" id="budgetForm">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="category">
                        Category
                    </label>
                    <select name="category" id="category" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2">
                        <option value="">Select a category</option>
                        @foreach($categories as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    
                    <input type="text" name="custom_category" id="custom_category"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline hidden"
                        placeholder="Enter custom category">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="amount">
                        Amount (Rs.)
                    </label>
                    <input type="number" step="0.01" name="amount" id="amount" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <p id="suggestion" class="text-sm text-gray-600 mt-1 hidden">
                        Suggested amount: <span id="suggestedAmount"></span>
                    </p>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('budgets.index') }}"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Cancel
                    </a>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Create Budget
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@php
    $debugSuggestedAmounts = json_encode($suggestedAmounts);
    echo "<script>console.log('PHP Debug - Suggested Amounts:', " . $debugSuggestedAmounts . ");</script>";
@endphp

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category');
    const customCategoryInput = document.getElementById('custom_category');
    const amountInput = document.getElementById('amount');
    const suggestionElement = document.getElementById('suggestion');
    const suggestedAmountElement = document.getElementById('suggestedAmount');
    
    // Store suggested amounts from PHP
    const suggestedAmounts = @json($suggestedAmounts);
    
    console.log('Initial suggested amounts:', suggestedAmounts);
    console.log('Total Income:', @json($totalIncome));
    console.log('Categories:', @json($categories));

    function updateCategory() {
        const selectedCategory = categorySelect.value;
        console.log('Selected category:', selectedCategory);
        
        if (selectedCategory === 'others') {
            customCategoryInput.classList.remove('hidden');
            customCategoryInput.required = true;
            suggestionElement.classList.add('hidden');
            amountInput.value = '';
        } else if (selectedCategory) {  // Only proceed if a category is selected
            customCategoryInput.classList.add('hidden');
            customCategoryInput.required = false;
            customCategoryInput.value = '';
            
            console.log('Checking suggestion for:', selectedCategory);
            console.log('Available suggestions:', suggestedAmounts);
            
            if (suggestedAmounts && typeof suggestedAmounts === 'object' && selectedCategory in suggestedAmounts) {
                const suggestedAmount = suggestedAmounts[selectedCategory];
                console.log('Found suggestion:', suggestedAmount);
                
                suggestedAmountElement.textContent = 'LKR ' + Number(suggestedAmount).toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                suggestionElement.classList.remove('hidden');
                amountInput.value = suggestedAmount;
            } else {
                console.log('No suggestion found for category:', selectedCategory);
                suggestionElement.classList.add('hidden');
                amountInput.value = '';
            }
        }
    }

    // Add event listener for category changes
    categorySelect.addEventListener('change', function(event) {
        console.log('Category changed to:', event.target.value);
        updateCategory();
    });
    
    // Set initial state
    updateCategory();
});
</script>
@endsection
