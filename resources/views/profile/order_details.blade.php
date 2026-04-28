@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Order Details - #{{ $order->order_number }}</h3>
                </div>
                <div class="card-body">
                    <!-- Order Status Timeline -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Order Status</h5>
                            <div class="progress mb-3">
                                @php
                                    $statuses = ['pending', 'processing', 'shipped', 'delivered'];
                                    $currentIndex = array_search($order->order_status, $statuses);
                                    $percentage = (($currentIndex + 1) / count($statuses)) * 100;
                                @endphp
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="row text-center">
                                <div class="col-3">
                                    <div class="status-step {{ $order->created_at ? 'completed' : '' }}">
                                        <i class="fas fa-check-circle"></i>
                                        <p>Order Placed</p>
                                        <small>{{ $order->created_at ? $order->created_at->format('d M Y') : '-' }}</small>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="status-step {{ $order->processing_at ? 'completed' : ($order->order_status == 'processing' ? 'active' : '') }}">
                                        <i class="fas fa-cog"></i>
                                        <p>Processing</p>
                                        <small>{{ $order->processing_at ? date('d M Y', strtotime($order->processing_at)) : '-' }}</small>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="status-step {{ $order->shipped_at ? 'completed' : ($order->order_status == 'shipped' ? 'active' : '') }}">
                                        <i class="fas fa-truck"></i>
                                        <p>Shipped</p>
                                        <small>{{ $order->shipped_at ? date('d M Y', strtotime($order->shipped_at)) : '-' }}</small>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="status-step {{ $order->delivered_at ? 'completed' : ($order->order_status == 'delivered' ? 'active' : '') }}">
                                        <i class="fas fa-home"></i>
                                        <p>Delivered</p>
                                        <small>{{ $order->delivered_at ? date('d M Y', strtotime($order->delivered_at)) : '-' }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Order Info -->
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Order Information</h5>
                            <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                            <p><strong>Order Date:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
                            <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                            <p><strong>Payment Status:</strong> <span class="badge bg-info">{{ ucfirst($order->payment_status) }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <h5>Shipping Information</h5>
                            <p><strong>Address:</strong> {{ $order->shipping_address }}</p>
                            <p><strong>Phone:</strong> {{ $order->phone }}</p>
                            @if($order->notes)
                                <p><strong>Notes:</strong> {{ $order->notes }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Order Items -->
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
                    
                    <!-- Cancel Order Button -->
                    @if(in_array($order->order_status, ['pending', 'processing']))
                    <form action="{{ route('orders.cancel', $order) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this order?')">Cancel Order</button>
                    </form>
                    @endif
                    
                    <a href="{{ route('profile.orders') }}" class="btn btn-secondary">Back to Orders</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.status-step {
    text-align: center;
    padding: 10px;
    border-radius: 5px;
}
.status-step i {
    font-size: 24px;
    margin-bottom: 5px;
}
.status-step.completed {
    color: #28a745;
}
.status-step.active {
    color: #007bff;
    font-weight: bold;
}
</style>
@endsection