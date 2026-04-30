@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Manage Orders</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                                <th>OTP Status</th>
                                <th>Verify Delivery</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>
                                    @if($order->is_guest)
                                        <span class="badge bg-secondary">Guest</span>
                                        {{ $order->guest_name }}
                                    @else
                                        {{ $order->user->name ?? 'N/A' }}
                                    @endif
                                </td>
                                <td>${{ number_format($order->total, 2) }}</td>
                                <td>
                                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="d-inline">
                                        @csrf
                                        <select name="order_status" class="form-control-sm" onchange="this.form.submit()">
                                            <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                            <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                            <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </form>
                                </td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('profile.order-details', $order->id) }}" class="btn btn-sm btn-primary">View</a>
                                </td>
                                
                                <!-- OTP Status Column -->
                                <td>
                                    @if($order->payment_method == 'cod')
                                        @if($order->otp_verified)
                                            <span class="badge bg-success">✓ Verified</span>
                                        @elseif($order->delivery_otp)
                                            <span class="badge bg-warning">OTP Sent</span>
                                        @else
                                            <span class="badge bg-secondary">Not Generated</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">N/A</span>
                                    @endif
                                </td>
                                
                                <!-- Verify Delivery Column -->
                                <td>
                                    @if($order->payment_method == 'cod' && !$order->otp_verified && $order->order_status == 'shipped' && $order->delivery_otp)
                                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#otpModal{{ $order->id }}">
                                            Verify OTP
                                        </button>
                                        
                                        <!-- OTP Modal -->
                                        <div class="modal fade" id="otpModal{{ $order->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('admin.orders.verify-otp', $order) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Verify Delivery OTP</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Order #: <strong>{{ $order->order_number }}</strong></p>
                                                            <p>Customer: 
                                                                @if($order->is_guest)
                                                                    {{ $order->guest_name }} (Guest)
                                                                @else
                                                                    {{ $order->user->name ?? 'N/A' }}
                                                                @endif
                                                            </p>
                                                            <div class="mb-3">
                                                                <label>Enter 6-digit OTP</label>
                                                                <input type="text" name="otp" class="form-control" maxlength="6" pattern="[0-9]{6}" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-success">Verify & Complete Delivery</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection