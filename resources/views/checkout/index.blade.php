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
                    <form action="{{ route('checkout.place') }}" method="POST">
                        @csrf
                        <h5>Shipping Information</h5>
                        <div class="mb-3">
                            <label>Shipping Address</label>
                            <textarea name="shipping_address" class="form-control" required>{{ old('shipping_address', auth()->user()->address) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label>Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', auth()->user()->phone) }}" required>
                        </div>
                        <div class="mb-3">
                            <label>Payment Method</label>
                            <select name="payment_method" class="form-control" required>
                                <option value="cod">Cash on Delivery</option>
                                <option value="card">Credit/Debit Card</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Order Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Place Order</button>
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