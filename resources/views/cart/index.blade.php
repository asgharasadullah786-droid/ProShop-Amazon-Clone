@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Shopping Cart</h1>
    
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @php $cart = session()->get('cart', []); @endphp
    
    @if(count($cart) > 0)
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    @php $total = 0; @endphp
                    @foreach($cart as $id => $item)
                    @php $itemTotal = $item['price'] * $item['quantity']; $total += $itemTotal; @endphp
                    <div class="row mb-3 cart-item" data-id="{{ $id }}">
                        <div class="col-md-2">
                            @if(isset($item['image']))
                            <img src="{{ asset($item['image']) }}" class="img-fluid" style="height: 80px; object-fit: cover;">
                            @endif
                        </div>
                        <div class="col-md-4">
                            <h6>{{ $item['name'] }}</h6>
                            <small class="text-muted">${{ number_format($item['price'], 2) }}</small>
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control cart-quantity" value="{{ $item['quantity'] }}" min="1" data-id="{{ $id }}">
                        </div>
                        <div class="col-md-2">
                            <span>${{ number_format($itemTotal, 2) }}</span>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-danger btn-sm remove-from-cart" data-id="{{ $id }}">Remove</button>
                        </div>
                    </div>
                    <hr>
                    @endforeach
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Cart Summary</h5>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total:</span>
                        <strong>${{ number_format($total, 2) }}</strong>
                    </div>
                    <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100">Proceed to Checkout</a>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary w-100 mt-2">Continue Shopping</a>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-info">Your cart is empty. <a href="{{ route('products.index') }}">Start shopping</a></div>
    @endif
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.cart-quantity').change(function() {
        let productId = $(this).data('id');
        let quantity = $(this).val();
        $.ajax({
            url: '{{ route("cart.update") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId,
                quantity: quantity
            },
            success: function() { location.reload(); }
        });
    });
    
    $('.remove-from-cart').click(function() {
        let productId = $(this).data('id');
        $.ajax({
            url: '{{ route("cart.remove") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId
            },
            success: function() { location.reload(); }
        });
    });
});
</script>
@endpush
@endsection