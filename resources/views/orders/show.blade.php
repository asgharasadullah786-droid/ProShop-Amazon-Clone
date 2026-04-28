@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Order Details</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Order Number:</strong> {{ $order->order_number }}
                        </div>
                        <div class="col-md-6">
                            <strong>Order Date:</strong> {{ $order->created_at->format('d M Y, h:i A') }}
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Order Status:</strong> 
                            <span class="badge bg-info">{{ ucfirst($order->order_status) }}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Payment Status:</strong> 
                            <span class="badge bg-warning">{{ ucfirst($order->payment_status) }}</span>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Shipping Address:</strong>
                            <p>{{ $order->shipping_address }}</p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Phone:</strong> {{ $order->phone }}
                        </div>
                        <div class="col-md-6">
                            <strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h5>Order Items</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>${{ number_format($item->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Subtotal:</th>
                                <th>${{ number_format($order->subtotal, 2) }}</th>
                            </tr>
                            @if($order->discount > 0)
                            <tr>
                                <th colspan="3" class="text-end">Discount:</th>
                                <th>-${{ number_format($order->discount, 2) }}</th>
                            </tr>
                            @endif
                            <tr>
                                <th colspan="3" class="text-end">Total:</th>
                                <th>${{ number_format($order->total, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                    
                    @if($order->notes)
                    <div class="row">
                        <div class="col-md-12">
                            <strong>Order Notes:</strong>
                            <p>{{ $order->notes }}</p>
                        </div>
                    </div>
                    @endif
                    
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Continue Shopping</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection