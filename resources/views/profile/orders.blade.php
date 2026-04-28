@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Profile Menu</div>
                <div class="card-body">
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('profile.index') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('profile.orders') }}">My Orders</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">My Orders</div>
                <div class="card-body">
                    @if($orders->count() > 0)
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                    <td>${{ number_format($order->total, 2) }}</td>
                                    <td><span class="badge bg-info">{{ ucfirst($order->order_status) }}</span></td>
                                    <td><a href="{{ route('profile.order-details', $order->id) }}" class="btn btn-sm btn-primary">View</a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $orders->links() }}
                    @else
                        <p>No orders found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection