@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Flash Sales / Daily Deals 🔥</h1>
    
    @if($flashSales->count() > 0)
        <div class="row">
            @foreach($flashSales as $flashSale)
            <div class="col-md-4 mb-4">
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">🔥 FLASH SALE</h5>
                    </div>
                    <div class="card-body">
                        @if($flashSale->product->image)
                        <img src="{{ asset($flashSale->product->image) }}" class="img-fluid mb-3" style="height: 200px; width: 100%; object-fit: cover;">
                        @endif
                        <h5>{{ $flashSale->product->name }}</h5>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <span class="h4 text-danger">${{ number_format($flashSale->sale_price, 2) }}</span>
                                <del class="text-muted ms-2">${{ number_format($flashSale->product->price, 2) }}</del>
                            </div>
                            <span class="badge bg-danger">Save {{ round((($flashSale->product->price - $flashSale->sale_price) / $flashSale->product->price) * 100) }}%</span>
                        </div>
                        
                        @php
                            $endTime = \Carbon\Carbon::parse($flashSale->end_time);
                            $now = \Carbon\Carbon::now();
                            $hoursLeft = $now->diffInHours($endTime, false);
                        @endphp
                        
                        <div class="alert alert-warning text-center">
                            <strong>⏰ Ends in:</strong> {{ $endTime->diffForHumans($now, true) }}
                        </div>
                        
                        @if($flashSale->sale_quantity > 0)
                        <div class="progress mb-2">
                            @php $sold = $flashSale->product->orderItems->sum('quantity'); @endphp
                            @php $percentage = min(100, ($sold / $flashSale->sale_quantity) * 100); @endphp
                            <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $percentage }}%">
                                {{ $sold }}/{{ $flashSale->sale_quantity }} sold
                            </div>
                        </div>
                        @endif
                        
                        <div class="d-grid gap-2">
                       @if(auth()->check() && auth()->user()->role == 'customer')
<form action="{{ route('flash-sale.add') }}" method="POST">
    @csrf
    <input type="hidden" name="flash_sale_id" value="{{ $flashSale->id }}">
    <input type="hidden" name="quantity" value="1">
    <button type="submit" class="btn btn-danger w-100">
        🛒 Buy Now at ${{ number_format($flashSale->sale_price, 2) }}
    </button>
</form>
@else
<button class="btn btn-secondary w-100" disabled>
    Login as customer to buy
</button>
@endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info">
            No active flash sales at the moment. Check back soon!
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
            },
            error: function(xhr) {
                alert('Error adding to cart');
            }
        });
    });
});
</script>
@endpush
@endsection