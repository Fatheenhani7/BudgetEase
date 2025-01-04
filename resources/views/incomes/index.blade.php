@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Your Income</h1>
    <div class="row">
        @foreach($incomes as $income)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $income->description }}</h5>
                        <p class="card-text">Amount: ${{ number_format($income->amount, 2) }}</p>
                        <p class="card-text">Category: {{ $income->category }}</p>
                        <p class="card-text">Date: {{ $income->date ? \Carbon\Carbon::parse($income->date)->format('Y-m-d') : 'N/A' }}</p>
                        <a href="{{ route('incomes.edit', $income) }}" class="btn btn-primary">Edit</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <a href="{{ route('incomes.create') }}" class="btn btn-success mt-3">Add New Income</a>
</div>
@endsection
