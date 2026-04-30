@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-0">✓ Unsubscribed Successfully</h3>
                </div>
                <div class="card-body text-center">
                    <p>You have been unsubscribed from price alerts for <strong>{{ $productName }}</strong>.</p>
                    <p>You will no longer receive price drop emails for this product.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">Continue Shopping</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection