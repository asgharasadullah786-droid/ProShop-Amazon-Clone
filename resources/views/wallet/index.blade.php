@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <h5>Wallet Balance</h5>
                    <h2>${{ number_format($wallet->balance, 2) }}</h2>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">Add Money to Wallet</div>
                <div class="card-body">
                    <form action="{{ route('wallet.add-balance') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label>Amount ($)</label>
                            <input type="number" name="amount" class="form-control" min="100" max="50000" required>
                            <small class="text-muted">Min: $100, Max: $50,000</small>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Add Balance</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Transaction History</div>
                <div class="card-body">
                    @if($transactions->count() > 0)
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Balance After</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->created_at->format('d M Y, h:i A') }}</td>
                                <td>
                                    @if($transaction->type == 'credit')
                                        <span class="badge bg-success">+ Credit</span>
                                    @else
                                        <span class="badge bg-danger">- Debit</span>
                                    @endif
                                </td>
                                <td>${{ number_format($transaction->amount, 2) }}</td>
                                <td>${{ number_format($transaction->balance_after, 2) }}</td>
                                <td>{{ $transaction->description }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $transactions->links() }}
                    @else
                    <p class="text-center">No transactions yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection