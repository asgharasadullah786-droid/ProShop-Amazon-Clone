@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-success text-white">
            <h3>✅ Order Placed Successfully!</h3>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <strong>Order #:</strong> {{ $order->order_number }}<br>
                <strong>Email:</strong> {{ $order->guest_email }}<br>
                <strong>Total:</strong> ${{ number_format($order->total, 2) }}
            </div>
            
            <p>A confirmation email has been sent to your email address.</p>
            
            <div class="alert alert-warning">
                <strong>📝 Want to track your order?</strong><br>
                Save this link or
                <a href="{{ route('guest.order.create-account', $order->guest_token) }}" class="btn btn-primary">
                    Create Account to Track Orders
                </a>
            </div>
            
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Continue Shopping</a>
        </div>
    </div>
</div>
@endsection