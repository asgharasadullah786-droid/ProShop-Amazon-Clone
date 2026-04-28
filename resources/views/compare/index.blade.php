@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Compare Products</h1>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @php $compareIds = session()->get('compare', []); @endphp
    
    @if(count($compareIds) > 0)
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Features</th>
                            @foreach($products as $product)
                            <th class="text-center">
                                {{ $product->name }}
                                <form action="{{ route('compare.remove') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="btn btn-sm btn-danger">✕</button>
                                </form>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Image</strong></td>
                            @foreach($products as $product)
                            <td class="text-center">
                                @if($product->image)
                                <img src="{{ asset($product->image) }}" style="height: 100px; object-fit: cover;">
                                @else
                                <div class="bg-secondary text-white p-3">No Image</div>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        <tr>
                            <td><strong>Price</strong></td>
                            @foreach($products as $product)
                            <td class="text-center">${{ number_format($product->price, 2) }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td><strong>Stock</strong></td>
                            @foreach($products as $product)
                            <td class="text-center">
                                <span class="badge bg-{{ $product->stock > 0 ? 'success' : 'danger' }}">
                                    {{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                                </span>
                            </td>
                            @endforeach
                        </tr>
                        <tr>
                            <td><strong>Description</strong></td>
                            @foreach($products as $product)
                            <td>{{ Str::limit($product->description, 100) }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td><strong>Rating</strong></td>
                            @foreach($products as $product)
                            <td class="text-center">⭐ {{ number_format($product->average_rating, 1) }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td><strong>Action</strong></td>
                            @foreach($products as $product)
                            <td class="text-center">
                                <button class="btn btn-sm btn-primary add-to-cart" data-id="{{ $product->id }}">Add to Cart</button>
                            </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
                
                <div class="mt-3">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Continue Shopping</a>
                    <a href="{{ route('compare.clear') }}" class="btn btn-danger" onclick="return confirm('Clear all products from compare?')">Clear All</a>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            No products to compare. <a href="{{ route('products.index') }}">Add products from shop page</a>
        </div>
    @endif
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
            }
        });
    });
});
</script>
@endpush
@endsection