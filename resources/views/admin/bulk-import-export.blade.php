@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">📊 Bulk Product Import / Export</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    @if(session('partial_success'))
                        <div class="alert alert-warning">{{ session('partial_success') }}</div>
                    @endif
                    
                    @if(session('import_errors'))
                        <div class="alert alert-danger">
                            <strong>Import Errors:</strong>
                            <ul>
                                @foreach(session('import_errors') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="row">
                        <!-- Export Section -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">📤 Export Products</h5>
                                </div>
                                <div class="card-body">
                                    <p>Export your products to CSV file.</p>
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('bulk.export') }}" class="btn btn-success">
                                            📥 Export All Products
                                        </a>
                                        <a href="{{ route('bulk.export.sample') }}" class="btn btn-info">
                                            📄 Download Sample Template
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Import Section -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0">📥 Import Products</h5>
                                </div>
                                <div class="card-body">
                                    <p>Upload CSV file to import products in bulk.</p>
                                    <form action="{{ route('bulk.import') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Choose CSV File</label>
                                            <input type="file" name="file" class="form-control" accept=".csv" required>
                                            <small class="text-muted">Supported format: .csv (Max 5MB)</small>
                                        </div>
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-warning">
                                                📤 Import Products
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sample CSV Format -->
                    <div class="card mt-3">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0">📋 Sample CSV Format</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Your CSV file should have the following columns:</p>
                            <pre class="bg-light p-3 rounded" style="overflow-x: auto;">
name,sku,price,compare_price,stock,category,description,status
Sample Product,SKU001,99.99,129.99,50,Electronics,"This is a sample product",active
Another Product,SKU002,49.99,59.99,100,Clothing,"Another description",active</pre>
                            <p class="text-muted mt-2">
                                <strong>Column Descriptions:</strong><br>
                                • <code>name</code> - Product name (required)<br>
                                • <code>sku</code> - Unique SKU (required)<br>
                                • <code>price</code> - Product price (required, numeric)<br>
                                • <code>compare_price</code> - Original price for discount (optional)<br>
                                • <code>stock</code> - Quantity in stock (optional, default 0)<br>
                                • <code>category</code> - Category name (will be auto-created)<br>
                                • <code>description</code> - Product description (optional)<br>
                                • <code>status</code> - active/inactive (optional, default active)
                            </p>
                            <a href="{{ route('bulk.export.sample') }}" class="btn btn-sm btn-info">
                                Download Sample CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection