@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            @if($product->image)
            <img src="{{ asset($product->image) }}" class="img-fluid rounded" alt="{{ $product->name }}">
            @endif
            
            @if($product->images)
            <div class="row mt-3">
                @foreach($product->images as $img)
                <div class="col-3">
                    <img src="{{ asset($img) }}" class="img-thumbnail" style="height: 100px; object-fit: cover;">
                </div>
                @endforeach
            </div>
            @endif
        </div>
        
        <div class="col-md-6">
            <h1>{{ $product->name }}</h1>
            <p class="text-muted">SKU: {{ $product->sku }} | Category: {{ $product->category->name }}</p>
            
            <div class="mb-3">
                <span class="display-6 text-primary">${{ number_format($product->price, 2) }}</span>
                @if($product->compare_price)
                <del class="text-muted ms-3">${{ number_format($product->compare_price, 2) }}</del>
                @endif
            </div>
            @if($product->stock <= 5)
    <div class="alert alert-warning mt-2">
        <i class="fas fa-exclamation-triangle"></i> 
        @if($product->stock <= 0)
            **Out of Stock** - This product is currently unavailable.
        @else
            **Only {{ $product->stock }} items left!** - Order soon before it's gone.
        @endif
    </div>
@endif
            <div class="mb-4">
                <h5>Description:</h5>
                <p>{{ $product->description }}</p>
            </div>
            
            @if($product->stock > 0)
            @if(auth()->check() && auth()->user()->role == 'customer')
<div class="d-flex gap-2">
    <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock }}" class="form-control" style="width: 80px;">
    <button class="btn btn-primary btn-lg flex-grow-1 add-to-cart" data-id="{{ $product->id }}">
        Add to Cart
    </button>
    <button class="btn btn-outline-danger btn-lg add-to-wishlist" data-id="{{ $product->id }}">
        ♥ Wishlist
    </button>
</div>
@elseif($product->stock > 0)
<div class="alert alert-info">
    Login as customer to purchase this product.
</div>
@endif
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.add-to-cart').click(function() {
        let productId = $(this).data('id');
        let quantity = $('#quantity').val();
        $.ajax({
            url: '{{ route("cart.add") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId,
                quantity: quantity
            },
            success: function(response) {
                alert(response.message);
                $('#cart-count').text(response.cart_count);
            }
        });
    });
    
    $('.add-to-wishlist').click(function() {
        let productId = $(this).data('id');
        $.ajax({
            url: '{{ route("wishlist.add") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId
            },
            success: function(response) {
                alert(response.message);
            }
        });
    });

    $('.add-to-compare').click(function() {
    let productId = $(this).data('id');

    $.ajax({
        url: '{{ route("compare.add") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            product_id: productId
        },
        success: function(response) {
            alert(response.message);
        }
    });
});
});
</script>
@endpush
@endsection