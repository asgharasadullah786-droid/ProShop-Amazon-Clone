@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3>Wallet Recharge</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>Amount to add:</strong> ${{ number_format($amount, 2) }}
                    </div>
                    
                    <form action="{{ route('wallet.payment.success') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label>Select Payment Method</label>
                            <select name="payment_method" class="form-control" required>
                                <option value="cod">Cash on Delivery</option>
                                <option value="bank">Bank Transfer</option>
                                <option value="easypaisa">EasyPaisa</option>
                                <option value="jazzcash">JazzCash</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Proceed to Pay</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection