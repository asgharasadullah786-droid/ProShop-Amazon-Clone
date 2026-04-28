@extends('layouts.app')

@section('content')
<div class="container">
    <h1>My Wishlist</h1>
    
    <div class="row">
        @forelse($wishlists as $wishlist)
        <div class="col-md-3 mb-4">
            <div class="card">
                @if($wishlist->product->image)
                <img src="{{ asset($wishlist->product->image) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                @endif
                <div class="card-body">
                    <h5>{{ $wishlist->product->name }}</h5>
                    <p class="text-primary">${{ number_format($wishlist->product->price, 2) }}</p>
                    <button class="btn btn-primary btn-sm add-to-cart" data-id="{{ $wishlist->product->id }}">Add to Cart</button>
                    <form action="{{ route('wishlist.remove', $wishlist->product->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">Your wishlist is empty.</div>
        </div>
        @endforelse
    </div>
</div>
@endsection