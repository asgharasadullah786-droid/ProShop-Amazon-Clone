@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Store Header -->
    <div class="card mb-4">
        <div class="card-body text-center">
            <div class="mb-3">
                @if($user->avatar)
                <img src="{{ asset($user->avatar) }}" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                @else
                <div class="bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px; font-size: 40px;">
                    {{ substr($user->name, 0, 1) }}
                </div>
                @endif
            </div>
            <h2>{{ $user->name }}'s Store</h2>
            <p class="text-muted">{{ $user->store_description ?? 'Welcome to my store!' }}</p>
            
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="border rounded p-2">
                        <h5>{{ $totalProducts }}</h5>
                        <small class="text-muted">Products</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-2">
                        <h5>{{ $totalSold }}</h5>
                        <small class="text-muted">Items Sold</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-2">
                        <h5>${{ number_format($totalRevenue, 2) }}</h5>
                        <small class="text-muted">Revenue</small>
                    </div>
                </div>
            </div>
            
            @if(auth()->check() && auth()->id() == $user->id && (auth()->user()->role == 'seller' || auth()->user()->role == 'admin'))
            <a href="{{ route('vendor.edit') }}" class="btn btn-primary mt-3" onclick="alert('Edit store feature coming soon!')">Edit Store</a>
            @endif
        </div>
    </div>
    
    <!-- Store Products -->
    <h3 class="mb-4">Products from {{ $user->name }}</h3>
    
    @if($products->count() > 0)
    <div class="row">
        @foreach($products as $product)
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                @if($product->image)
                <img src="{{ asset($product->image) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="text-primary">${{ number_format($product->price, 2) }}</p>
                    <a href="{{ route('products.show', $product) }}" class="btn btn-primary btn-sm">View Details</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    {{ $products->links() }}
    @else
    <div class="alert alert-info">No products in this store yet.</div>
    @endif
</div>
@endsection