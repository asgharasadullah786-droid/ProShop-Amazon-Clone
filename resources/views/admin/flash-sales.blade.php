@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3>Manage Flash Sales</h3>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            <!-- Add Flash Sale Form -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">Create New Flash Sale</div>
                <div class="card-body">
                    <form action="{{ route('admin.flash-sales.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <label>Product</label>
                                <select name="product_id" class="form-control" required>
                                    <option value="">Select Product</option>
                                    @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} - ${{ $product->price }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label>Sale Price ($)</label>
                                <input type="number" step="0.01" name="sale_price" class="form-control" required>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label>Sale Quantity</label>
                                <input type="number" name="sale_quantity" class="form-control" value="0">
                            </div>
                            <div class="col-md-2 mb-2">
                                <label>Start Time</label>
                                <input type="datetime-local" name="start_time" class="form-control" required>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label>End Time</label>
                                <input type="datetime-local" name="end_time" class="form-control" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Create Flash Sale</button>
                    </form>
                </div>
            </div>
            
            <!-- Existing Flash Sales -->
            <h4>Active Flash Sales</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Original Price</th>
                        <th>Sale Price</th>
                        <th>Discount</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($flashSales as $flashSale)
                    <tr>
                        <td>{{ $flashSale->product->name }}</td>
                        <td>${{ number_format($flashSale->product->price, 2) }}</td>
                        <td>${{ number_format($flashSale->sale_price, 2) }}</td>
                        <td>{{ round((($flashSale->product->price - $flashSale->sale_price) / $flashSale->product->price) * 100) }}%</td>
                        <td>{{ $flashSale->start_time }}</td>
                        <td>{{ $flashSale->end_time }}</td>
                        <td>
                            @php
                                $now = new \DateTime('now', new \DateTimeZone('Asia/Karachi'));
                                $start = new \DateTime($flashSale->start_time);
                                $end = new \DateTime($flashSale->end_time);
                                $isActive = ($now >= $start && $now <= $end);
                            @endphp
                            <span class="badge bg-{{ $isActive ? 'success' : 'danger' }}">
                                {{ $isActive ? 'Active' : 'Expired' }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('admin.flash-sales.destroy', $flashSale) }}" method="POST" onsubmit="return confirm('Delete this flash sale?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            {{ $flashSales->links() }}
        </div>
    </div>
</div>
@endsection