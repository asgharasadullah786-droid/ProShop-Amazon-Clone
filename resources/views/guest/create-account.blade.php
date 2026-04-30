@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3>Create Account for Better Experience</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>Order #:</strong> {{ $order->order_number }}<br>
                        <strong>Email:</strong> {{ $order->guest_email }}
                    </div>
                    
                    <form action="{{ route('guest.order.register', $token) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Create Account & Link Order</button>
                    </form>
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('guest.order.track', $token) }}">Skip for now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection