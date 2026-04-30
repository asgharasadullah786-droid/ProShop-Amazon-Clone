@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Search Bar -->
    <div class="row mb-4">
        <div class="col-md-8 mx-auto">
            <form action="{{ route('products.search') }}" method="GET" id="searchForm">
                <div class="input-group">
                    <input type="text" name="search" class="form-control form-control-lg" 
                           placeholder="Search products..." value="{{ request('search') }}">
                    <button class="btn btn-primary btn-lg" type="submit">
                        🔍 Search
                    </button>
                </div>
                
                <!-- Filters -->
                <div class="row mt-3">
                    <div class="col-md-3">
                        <select name="category" class="form-control">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="min_price" class="form-control" 
                               placeholder="Min Price" value="{{ request('min_price') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="max_price" class="form-control" 
                               placeholder="Max Price" value="{{ request('max_price') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="sort" class="form-control">
                            <option value="">Sort by</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>All Products</h1>
                @auth
                    @if(auth()->user()->role == 'admin' || auth()->user()->role == 'seller')
                        <a href="{{ route('products.create') }}" class="btn btn-primary">Add New Product</a>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <div class="row">
        @forelse($products as $product)
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                @if($product->image)
                <img src="{{ asset($product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                @else
                <div class="bg-secondary text-white text-center py-5">No Image</div>
                @endif
                
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text text-muted">{{ Str::limit($product->description, 100) }}</p>
                    <small class="text-muted d-block">
                       By <a href="{{ route('vendor.storefront', $product->user) }}">{{ $product->user->name }}</a>
                    </small>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="h5 text-primary">${{ number_format($product->price, 2) }}</span>
                            @if($product->compare_price)
                            <del class="text-muted ms-2">${{ number_format($product->compare_price, 2) }}</del>
                            @endif
                        </div>
                        @if($product->discount_percent > 0)
                        <span class="badge bg-danger">-{{ $product->discount_percent }}%</span>
                        @endif
                    </div>
                </div>
                
                <div class="card-footer bg-white">
                    <div class="d-grid gap-2">
                        <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary btn-sm">View Details</a>
                        
                        <!-- Show Add to Cart for Guests AND Customers -->
                        @if(!auth()->check() || auth()->user()->role == 'customer')
                            <button class="btn btn-outline-success btn-sm add-to-cart" data-id="{{ $product->id }}">Add to Cart</button>
                        @endif
                        
                        <!-- Show Compare only for logged-in customers -->
                        @auth
                            @if(auth()->user()->role == 'customer')
                                <button class="btn btn-outline-info btn-sm add-to-compare mt-1" data-id="{{ $product->id }}">
                                    📊 Compare
                                </button>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">No products found. Create your first product!</div>
        </div>
        @endforelse
    </div>

    <div class="row">
        <div class="col-12">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection

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
                alert('Error: ' + (xhr.responseJSON?.message || 'Something went wrong'));
            }
        });
    });
});
</script>
@endpush