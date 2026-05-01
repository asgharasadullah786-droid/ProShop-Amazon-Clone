<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ProShop - Your Shopping Destination</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Lightbox CSS -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">ProShop</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('products.index') }}">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('flash-sales.index') }}">Flash Sales</a>
                </li>
                
                <!-- Cart - Show for EVERYONE (guests + logged in) -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('cart.index') }}">
                        🛒 Cart <span id="cart-count" class="badge bg-danger">{{ count(session()->get('cart', [])) }}</span>
                    </a>
                </li>
                
                <!-- Compare - Show only for logged in customers -->
                @auth
                    @if(auth()->user()->role == 'customer')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('compare.index') }}">
                            📊 Compare <span id="compare-count" class="badge bg-info">{{ count(session()->get('compare', [])) }}</span>
                        </a>
                    </li>
                    @endif
                @endauth
                
                <!-- Wishlist - Show only for logged in customers -->
                @auth
                    @if(auth()->user()->role == 'customer')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('wishlist.index') }}">❤️ Wishlist</a>
                    </li>
                    @endif
                @endauth
            </ul>
            
            <ul class="navbar-nav">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            @if(auth()->user()->role == 'customer')
                                <a class="dropdown-item" href="{{ route('profile.index') }}">👤 My Profile</a>
                                <a class="dropdown-item" href="{{ route('profile.orders') }}">📦 My Orders</a>
                                <a class="dropdown-item" href="{{ route('addresses.index') }}">📍 My Addresses</a>
                                <a class="dropdown-item" href="{{ route('wallet.index') }}">💰 My Wallet</a>
                            @endif
                            
                            @if(auth()->user()->role == 'admin')
                                <a class="dropdown-item bg-primary text-white" href="{{ route('admin.dashboard') }}">⚡ Admin Dashboard</a>
                                <a class="dropdown-item" href="{{ route('admin.orders') }}">📋 Manage Orders</a>
                                <a class="dropdown-item" href="{{ route('admin.users') }}">👥 Manage Users</a>
                                <a class="dropdown-item" href="{{ route('admin.flash-sales') }}">🔥 Manage Flash Sales</a>
                                <a class="dropdown-item" href="{{ route('bulk.index') }}">📊 Bulk Import/Export</a>
                            @endif
                            
                            @if(auth()->user()->role == 'seller')
                                <a class="dropdown-item" href="{{ route('products.create') }}">➕ Add Product</a>
                                <a class="dropdown-item" href="{{ route('vendor.storefront', auth()->id()) }}">🏪 My Store</a>
                                <a class="dropdown-item" href="{{ route('vendor.edit') }}">✏️ Edit Store</a>
                            @endif
                            
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                🚪 Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script>
        $(document).ready(function() {
            // Update cart count
            function updateCartCount() {
                $.ajax({
                    url: '{{ route("cart.index") }}',
                    method: 'GET',
                    success: function(response) {
                        // This will be implemented later
                    }
                });
            }

            // Add to Cart functionality
            $(document).on('click', '.add-to-cart', function(e) {
                e.preventDefault();
                let productId = $(this).data('id');
                let quantity = $('#quantity').length ? $('#quantity').val() : 1;
                
                $.ajax({
                    url: '{{ route("cart.add") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        product_id: productId,
                        quantity: quantity
                    },
                    success: function(response) {
                        if(response.success) {
                            alert(response.message);
                            $('#cart-count').text(response.cart_count);
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('Error: ' + xhr.responseJSON.message);
                    }
                });
            });

            // Add to Wishlist functionality
            $(document).on('click', '.add-to-wishlist', function(e) {
                e.preventDefault();
                let productId = $(this).data('id');
                
                $.ajax({
                    url: '{{ route("wishlist.add") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        product_id: productId
                    },
                    success: function(response) {
                        alert(response.message);
                    },
                    error: function(xhr) {
                        alert('Error: ' + xhr.responseJSON.message);
                    }
                });
            });
            // Add to Compare functionality
$(document).on('click', '.add-to-compare', function(e) {
    e.preventDefault();
    let productId = $(this).data('id');
    
    $.ajax({
        url: '{{ route("compare.add") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            product_id: productId
        },
        success: function(response) {
            if(response.success) {
                alert(response.message);
                // Update compare count in navbar
                $.get('{{ route("compare.count") }}', function(data) {
                    $('#compare-count').text(data.count);
                });
            } else {
                alert(response.message);
            }
        },
        error: function(xhr) {
            alert('Error: ' + xhr.responseJSON.message);
        }
    });
});
        });
    </script>
    @stack('scripts')

<!-- Lightbox JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>    
</body>
</html>