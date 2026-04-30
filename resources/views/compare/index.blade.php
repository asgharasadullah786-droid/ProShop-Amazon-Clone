@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Compare Products</h1>
        @if(session()->has('compare') && count(session('compare')) > 0)
        <form action="{{ route('compare.remove-all') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger" onclick="return confirm('Remove all products from comparison?')">
                🗑️ Clear All
            </button>
        </form>
        @endif
    </div>
    
    @php $compareIds = session()->get('compare', []); @endphp
    
    @if(count($products) > 0)
        <div class="table-responsive">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th style="width: 200px;">Features</th>
                        @foreach($products as $product)
                        <th style="min-width: 250px;">
                            <div class="position-relative">
                                {{ $product->name }}
                                <form action="{{ route('compare.remove') }}" method="POST" class="position-absolute top-0 end-0">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="btn btn-sm btn-danger rounded-circle">✕</button>
                                </form>
                            </div>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <!-- Product Image -->
                    <tr>
                        <td><strong>Image</strong></td>
                        @foreach($products as $product)
                        <td>
                            @if($product->image)
                            <img src="{{ asset($product->image) }}" class="img-fluid" style="height: 150px; object-fit: cover;">
                            @else
                            <div class="bg-secondary text-white p-3">No Image</div>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    
                    <!-- Price -->
                    <tr>
                        <td><strong>Price</strong></td>
                        @foreach($products as $product)
                        <td>
                            <span class="h5 text-primary">${{ number_format($product->price, 2) }}</span>
                            @if($product->compare_price)
                            <br><del class="text-muted">${{ number_format($product->compare_price, 2) }}</del>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    
                    <!-- Discount -->
                    <tr>
                        <td><strong>Discount</strong></td>
                        @foreach($products as $product)
                        <td>
                            @if($product->discount_percent > 0)
                            <span class="badge bg-danger">-{{ $product->discount_percent }}%</span>
                            @else
                            <span class="text-muted">No discount</span>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    
                    <!-- Stock Status -->
                    <tr>
                        <td><strong>Availability</strong></td>
                        @foreach($products as $product)
                        <td>
                            @if($product->stock > 0)
                                <span class="badge bg-success">In Stock ({{ $product->stock }})</span>
                            @else
                                <span class="badge bg-danger">Out of Stock</span>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    
                    <!-- Rating -->
                    <tr>
                        <td><strong>Rating</strong></td>
                        @foreach($products as $product)
                        <td>
                            @if($product->average_rating > 0)
                                <div>
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= round($product->average_rating))
                                            ⭐
                                        @else
                                            ☆
                                        @endif
                                    @endfor
                                    <br>
                                    <small class="text-muted">({{ $product->reviews->count() }} reviews)</small>
                                </div>
                            @else
                                <span class="text-muted">No ratings yet</span>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    
                    <!-- Description -->
                    <tr>
                        <td><strong>Description</strong></td>
                        @foreach($products as $product)
                        <td class="text-start">
                            {{ Str::limit($product->description, 150) }}
                        </td>
                        @endforeach
                    </tr>
                    
                    <!-- SKU -->
                    <tr>
                        <td><strong>SKU</strong></td>
                        @foreach($products as $product)
                        <td>{{ $product->sku }}</td>
                        @endforeach
                    </tr>
                    
                    <!-- Category -->
                    <tr>
                        <td><strong>Category</strong></td>
                        @foreach($products as $product)
                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                        @endforeach
                    </tr>
                    
                    <!-- Seller -->
                    <tr>
                        <td><strong>Seller</strong></td>
                        @foreach($products as $product)
                        <td>
                            <a href="{{ route('vendor.storefront', $product->user_id) }}">
                                {{ $product->user->name }}
                            </a>
                        </td>
                        @endforeach
                    </tr>
                    
                    <!-- Action Buttons -->
                    <tr>
                        <td><strong>Action</strong></td>
                        @foreach($products as $product)
                        <td>
                            @if($product->stock > 0)
                                @if(auth()->check() && auth()->user()->role == 'customer')
                                <button class="btn btn-primary btn-sm w-100 add-to-cart" data-id="{{ $product->id }}">
                                    🛒 Add to Cart
                                </button>
                                @else
                                <button class="btn btn-secondary btn-sm w-100" disabled>
                                    Login to Buy
                                </button>
                                @endif
                            @else
                                <button class="btn btn-secondary btn-sm w-100" disabled>
                                    Out of Stock
                                </button>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info text-center">
            <h4>No products to compare</h4>
            <p>Go to <a href="{{ route('products.index') }}">Products</a> and click "Compare" button on products you want to compare.</p>
        </div>
    @endif
    
    <div class="mt-4">
        <a href="{{ route('products.index') }}" class="btn btn-secondary">← Continue Shopping</a>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.add-to-cart').click(function() {
        let productId = $(this).data('id');
        $.ajax({
            url: '{{ route("cart.add") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId,
                quantity: 1
            },
            success: function(response) {
                alert(response.message);
                $('#cart-count').text(response.cart_count);
            },
            error: function(xhr) {
                alert('Error adding to cart: ' + xhr.responseJSON.message);
            }
        });
    });
});
</script>
@endpush
@endsection