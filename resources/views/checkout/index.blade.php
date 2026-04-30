@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Checkout</h1>
    
    @php $cart = session()->get('cart', []); @endphp
    @php $total = 0; foreach($cart as $item) { $total += $item['price'] * $item['quantity']; } @endphp
    
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('checkout.place') }}" method="POST" id="checkoutForm">
                        @csrf
                        
                        @guest
                        <div class="alert alert-info">
                            <strong>Checkout as Guest</strong><br>
                            You can create an account after placing your order.
                            <a href="{{ route('login') }}">Login</a> if you already have an account.
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Full Name *</label>
                                <input type="text" name="guest_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Email Address *</label>
                                <input type="email" name="guest_email" class="form-control" required>
                            </div>
                        </div>
                        @else
                        <div class="alert alert-success">
                            ✅ You are logged in as <strong>{{ auth()->user()->name }}</strong>
                            <input type="hidden" name="guest_name" value="{{ auth()->user()->name }}">
                            <input type="hidden" name="guest_email" value="{{ auth()->user()->email }}">
                        </div>
                        @endguest
                        
                        <h5>Shipping Information</h5>
                        <div class="mb-3">
                            <label>Shipping Address *</label>
                            <textarea name="shipping_address" class="form-control" required>{{ old('shipping_address', auth()->user()->address ?? '') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label>Phone Number *</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', auth()->user()->phone ?? '') }}" required>
                        </div>
                        <div class="mb-3">
                            <label>Payment Method *</label>
                            <select name="payment_method" id="payment_method" class="form-control" required>
                                <option value="cod">💵 Cash on Delivery</option>
                                <option value="card">💳 Credit/Debit Card</option>
                                @auth
                                <option value="wallet">👛 Wallet (Balance: ${{ number_format(auth()->user()->wallet?->balance ?? 0, 2) }})</option>
                                @endauth
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label>Order Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="3"></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Place Order</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <h5>Order Summary</h5>
                    <hr>
                    @foreach($cart as $item)
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ $item['name'] }} x {{ $item['quantity'] }}</span>
                        <span>${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                    </div>
                    @endforeach
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total:</strong>
                        <strong>${{ number_format($total, 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('checkoutForm')?.addEventListener('submit', function(e) {
    let paymentMethod = document.getElementById('payment_method')?.value;
    if (paymentMethod === 'wallet') {
        @guest
        e.preventDefault();
        alert('Please login to use wallet payment.');
        return false;
        @endguest
    }
});
</script>
@endpush