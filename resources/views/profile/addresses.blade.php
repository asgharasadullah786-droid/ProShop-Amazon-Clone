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
                            <a class="nav-link" href="{{ route('profile.orders') }}">My Orders</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('addresses.index') }}">Addresses</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <span>My Addresses</span>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAddressModal">+ Add Address</button>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    <div class="row">
                        @foreach($addresses as $address)
                        <div class="col-md-6 mb-3">
                            <div class="card {{ $address->is_default ? 'border-primary' : '' }}">
                                <div class="card-body">
                                    @if($address->is_default)
                                        <span class="badge bg-primary float-end">Default</span>
                                    @endif
                                    <h6>{{ $address->label }}</h6>
                                    <p class="mb-1"><strong>{{ $address->full_name }}</strong></p>
                                    <p class="mb-1">{{ $address->phone }}</p>
                                    <p class="mb-1">{{ $address->address }}</p>
                                    <p class="mb-1">{{ $address->city }}, {{ $address->state }} {{ $address->zip_code }}</p>
                                    <div class="mt-2">
                                        @if(!$address->is_default)
                                            <form action="{{ route('addresses.set-default', $address) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-primary">Set Default</button>
                                            </form>
                                        @endif
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editAddressModal{{ $address->id }}">Edit</button>
                                        <form action="{{ route('addresses.destroy', $address) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this address?')">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Edit Modal -->
                        <div class="modal fade" id="editAddressModal{{ $address->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('addresses.update', $address) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Address</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-2">
                                                <label>Label</label>
                                                <input type="text" name="label" class="form-control" value="{{ $address->label }}" required>
                                            </div>
                                            <div class="mb-2">
                                                <label>Full Name</label>
                                                <input type="text" name="full_name" class="form-control" value="{{ $address->full_name }}" required>
                                            </div>
                                            <div class="mb-2">
                                                <label>Phone</label>
                                                <input type="text" name="phone" class="form-control" value="{{ $address->phone }}" required>
                                            </div>
                                            <div class="mb-2">
                                                <label>Address</label>
                                                <textarea name="address" class="form-control" required>{{ $address->address }}</textarea>
                                            </div>
                                            <div class="mb-2">
                                                <label>City</label>
                                                <input type="text" name="city" class="form-control" value="{{ $address->city }}" required>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" name="is_default" class="form-check-input" value="1" {{ $address->is_default ? 'checked' : '' }}>
                                                <label class="form-check-label">Set as default address</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Address Modal -->
<div class="modal fade" id="addAddressModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('addresses.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Address</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Label (e.g., Home, Office)</label>
                        <input type="text" name="label" class="form-control" value="Home" required>
                    </div>
                    <div class="mb-2">
                        <label>Full Name</label>
                        <input type="text" name="full_name" class="form-control" value="{{ auth()->user()->name }}" required>
                    </div>
                    <div class="mb-2">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ auth()->user()->phone }}" required>
                    </div>
                    <div class="mb-2">
                        <label>Address</label>
                        <textarea name="address" class="form-control" required></textarea>
                    </div>
                    <div class="mb-2">
                        <label>City</label>
                        <input type="text" name="city" class="form-control" required>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_default" class="form-check-input" value="1">
                        <label class="form-check-label">Set as default address</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Address</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection