@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Your Expenses</h1>
    <div class="row">
        @foreach($expenses as $expense)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $expense->description }}</h5>
                        <p class="card-text">Amount: ${{ number_format($expense->amount, 2) }}</p>
                        <p class="card-text">Category: {{ $expense->category }}</p>
                        <p class="card-text">Date: {{ $expense->date instanceof \Carbon\Carbon ? $expense->date->format('Y-m-d') : $expense->date }}</p>
                        <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <a href="{{ route('expenses.create') }}" class="btn btn-success mt-3">Add New Expense</a>
</div>
@endsection
