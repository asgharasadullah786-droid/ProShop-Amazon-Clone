@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- Product Images Section -->
        <div class="col-md-6">
            @if($product->image)
            <img src="{{ asset($product->image) }}" class="img-fluid rounded" alt="{{ $product->name }}">
            @endif
            
            @if($product->images)
            <div class="row mt-3">
                @foreach($product->images as $img)
                <div class="col-3">
                    <img src="{{ asset($img) }}" class="img-thumbnail" style="height: 100px; object-fit: cover;">
                </div>
                @endforeach
            </div>
            @endif
        </div>
        
        <!-- Product Details Section -->
        <div class="col-md-6">
            <h1>{{ $product->name }}</h1>
            <p class="text-muted">SKU: {{ $product->sku }} | Category: {{ $product->category->name }}</p>
            
            <div class="mb-3">
                <span class="display-6 text-primary">${{ number_format($product->price, 2) }}</span>
                @if($product->compare_price)
                <del class="text-muted ms-3">${{ number_format($product->compare_price, 2) }}</del>
                @endif
            </div>
            
            <div class="mb-3">
                <small class="text-muted">
                    Sold by: 
                    <a href="{{ route('vendor.storefront', $product->user) }}" class="text-primary">
                        {{ $product->user->name }}
                    </a>
                </small>
            </div>

            <!-- Stock Alert -->
            @if($product->stock <= 5)
                <div class="alert alert-warning mt-2">
                    <i class="fas fa-exclamation-triangle"></i> 
                    @if($product->stock <= 0)
                        **Out of Stock** - This product is currently unavailable.
                    @else
                        **Only {{ $product->stock }} items left!** - Order soon before it's gone.
                    @endif
                </div>
            @endif
            
            <div class="mb-4">
                <h5>Description:</h5>
                <p>{{ $product->description }}</p>
            </div>
            
            <!-- Add to Cart / Out of Stock Section -->
            @if($product->stock > 0)
                @if(!auth()->check() || auth()->user()->role == 'customer')
                    <div class="d-flex gap-2 flex-wrap">
                        <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock }}" class="form-control" style="width: 80px;">
                        <button class="btn btn-primary btn-lg add-to-cart" data-id="{{ $product->id }}">
                            🛒 Add to Cart
                        </button>
                        <button type="button" class="btn btn-outline-warning btn-lg" data-bs-toggle="modal" data-bs-target="#priceAlertModal">
                            💰 Price Alert
                        </button>
                        @auth
                            @if(auth()->user()->role == 'customer')
                                <button class="btn btn-outline-danger btn-lg add-to-wishlist" data-id="{{ $product->id }}">
                                    ♥ Wishlist
                                </button>
                                <button class="btn btn-outline-info btn-lg add-to-compare" data-id="{{ $product->id }}">
                                    📊 Compare
                                </button>
                            @endif
                        @endauth
                    </div>
                @else
                    <div class="alert alert-info">
                        🔒 Login as customer to purchase this product.
                    </div>
                @endif
                
                <!-- Admin Actions (Edit/Delete) - Shown for Admin and Seller -->
                @auth
                    @if(auth()->user()->role == 'admin' || auth()->user()->role == 'seller')
                        <div class="mt-4 pt-3 border-top">
                            <div class="d-flex gap-2">
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">
                                    ✏️ Edit Product
                                </a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this product permanently?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        🗑️ Delete Product
                                    </button>
                                </form>
                                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                    ← Back to Products
                                </a>
                            </div>
                        </div>
                    @endif
                @endauth
                
            @else
                <!-- Out of Stock with Notification -->
                <div class="alert alert-danger">
                    ❌ Out of Stock
                </div>
                
                <div class="card mt-3 border-warning">
                    <div class="card-body">
                        <h6 class="text-warning">📧 Get notified when back in stock</h6>
                        <form id="notifyForm" class="mt-3">
                            @csrf
                            <div class="row g-2">
                                <div class="col-md-12 mb-2">
                                    <input type="email" id="notify_email" class="form-control" placeholder="Your email address *" required>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <input type="text" id="notify_name" class="form-control" placeholder="Your name (optional)">
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-warning w-100" id="notifyBtn">
                                        🔔 Notify Me When Available
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div id="notifyMessage" class="mt-2"></div>
                        <small class="text-muted">We'll send you one email when this product is back in stock.</small>
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <!-- ========== REVIEWS SECTION ========== -->
    <div class="row mt-5">
        <div class="col-12">
            <h3>📝 Customer Reviews</h3>
            <hr>
            
            <!-- Review Form (Only for logged in customers) -->
            @auth
                @if(auth()->user()->role == 'customer')
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">✍️ Write a Review</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Your Rating</label>
                                <select name="rating" class="form-select" required>
                                    <option value="5">⭐⭐⭐⭐⭐ - Excellent</option>
                                    <option value="4">⭐⭐⭐⭐ - Good</option>
                                    <option value="3">⭐⭐⭐ - Average</option>
                                    <option value="2">⭐⭐ - Poor</option>
                                    <option value="1">⭐ - Terrible</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Your Review</label>
                                <textarea name="comment" class="form-control" rows="4" placeholder="Share your experience with this product..." required></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">📸 Upload Photos (Optional)</label>
                                <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                                <small class="text-muted">You can upload up to 5 images (JPG, PNG, GIF). Max 2MB each.</small>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Submit Review</button>
                        </form>
                    </div>
                </div>
                @endif
            @endauth
            
            <!-- Reviews Display -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">📝 Customer Reviews ({{ $product->reviews->count() }})</h5>
                </div>
                <div class="card-body">
                    @forelse($product->reviews->where('is_approved', true) as $review)
                    <div class="review-item mb-4 pb-3 border-bottom">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>{{ $review->user->name }}</strong>
                                <div class="text-warning">
                                    @for($i=1; $i<=5; $i++)
                                        @if($i <= $review->rating)
                                            ⭐
                                        @else
                                            ☆
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                        </div>
                        
                        <p class="mt-2">{{ $review->comment }}</p>
                        
                        <!-- Review Images Gallery -->
                        @if($review->has_images && $review->images)
                        <div class="review-images mt-2">
                            <div class="row g-2">
                                @foreach($review->images as $image)
                                <div class="col-auto">
                                    <a href="{{ asset('uploads/reviews/' . $image) }}" data-lightbox="review-{{ $review->id }}">
                                        <img src="{{ asset('uploads/reviews/' . $image) }}" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        <!-- Admin Actions -->
                        @auth
                            @if(auth()->user()->role == 'admin')
                            <div class="mt-2">
                                <form action="{{ route('reviews.toggle', $review) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning">
                                        {{ $review->is_approved ? 'Hide' : 'Approve' }}
                                    </button>
                                </form>
                                <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this review?')">Delete</button>
                                </form>
                            </div>
                            @endif
                        @endauth
                    </div>
                    @empty
                    <p class="text-muted">No reviews yet. Be the first to review this product!</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Price Alert Modal -->
<div class="modal fade" id="priceAlertModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">💰 Price Drop Alert</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="priceAlertForm">
                @csrf
                <div class="modal-body">
                    <p>Get notified when <strong>{{ $product->name }}</strong> drops to your desired price.</p>
                    <div class="mb-3">
                        <label class="form-label">Current Price</label>
                        <input type="text" class="form-control" value="${{ number_format($product->price, 2) }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Your Desired Price *</label>
                        <input type="number" id="desired_price" class="form-control" 
                               step="0.01" min="0" max="{{ $product->price }}" required>
                        <small class="text-muted">We'll notify you when price drops below this amount</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Your Email *</label>
                        <input type="email" id="alert_email" class="form-control" 
                               value="{{ auth()->check() ? auth()->user()->email : '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Your Name (Optional)</label>
                        <input type="text" id="alert_name" class="form-control" 
                               value="{{ auth()->check() ? auth()->user()->name : '' }}">
                    </div>
                    <div id="alertMessage"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning" id="alertSubmitBtn">
                        💰 Subscribe to Alert
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Lightbox CSS for image gallery -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet">

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
<script>
$(document).ready(function() {
    // Add to Cart
    $('.add-to-cart').click(function() {
        let productId = $(this).data('id');
        let quantity = $('#quantity').val();
        $.ajax({
            url: '{{ route("cart.add") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId,
                quantity: quantity
            },
            success: function(response) {
                alert(response.message);
                $('#cart-count').text(response.cart_count);
            },
            error: function(xhr) {
                alert('Error: ' + (xhr.responseJSON?.message || 'Something went wrong'));
            }
        });
    });
    
    // Add to Wishlist
    $('.add-to-wishlist').click(function() {
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
                alert('Error: ' + (xhr.responseJSON?.message || 'Something went wrong'));
            }
        });
    });

    // Add to Compare
    $('.add-to-compare').click(function() {
        let productId = $(this).data('id');
        $.ajax({
            url: '{{ route("compare.add") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId
            },
            success: function(response) {
                alert(response.message);
            },
            error: function(xhr) {
                alert('Error: ' + (xhr.responseJSON?.message || 'Something went wrong'));
            }
        });
    });
    
    // ========== BACK IN STOCK NOTIFICATION ==========
    if ($('#notifyForm').length) {
        $('#notifyForm').on('submit', function(e) {
            e.preventDefault();
            let email = $('#notify_email').val();
            let name = $('#notify_name').val();
            let productId = {{ $product->id }};
            
            if (!email) {
                $('#notifyMessage').html('<div class="alert alert-danger alert-sm">Please enter your email address.</div>');
                return;
            }
            
            $('#notifyBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Subscribing...');
            
            $.ajax({
                url: '{{ route("stock.notify", $product) }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    email: email,
                    name: name
                },
                success: function(response) {
                    if(response.success) {
                        $('#notifyMessage').html('<div class="alert alert-success alert-sm">' + response.message + '</div>');
                        $('#notifyForm')[0].reset();
                    } else {
                        $('#notifyMessage').html('<div class="alert alert-danger alert-sm">' + response.message + '</div>');
                    }
                    $('#notifyBtn').prop('disabled', false).html('🔔 Notify Me When Available');
                },
                error: function(xhr) {
                    let errorMsg = xhr.responseJSON?.message || 'Something went wrong. Please try again.';
                    $('#notifyMessage').html('<div class="alert alert-danger alert-sm">' + errorMsg + '</div>');
                    $('#notifyBtn').prop('disabled', false).html('🔔 Notify Me When Available');
                }
            });
        });
    }

    // Price Alert Form Submit
    $('#priceAlertForm').on('submit', function(e) {
        e.preventDefault();
        let desiredPrice = $('#desired_price').val();
        let email = $('#alert_email').val();
        let name = $('#alert_name').val();
        let productId = {{ $product->id }};
        
        if (!desiredPrice || !email) {
            $('#alertMessage').html('<div class="alert alert-danger">Please fill all required fields.</div>');
            return;
        }
        
        $('#alertSubmitBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Subscribing...');
        
        $.ajax({
            url: '{{ route("price-alert.subscribe", $product) }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                desired_price: desiredPrice,
                email: email,
                name: name
            },
            success: function(response) {
                if(response.success) {
                    $('#alertMessage').html('<div class="alert alert-success">' + response.message + '</div>');
                    setTimeout(function() {
                        $('#priceAlertModal').modal('hide');
                        $('#priceAlertForm')[0].reset();
                    }, 2000);
                } else {
                    $('#alertMessage').html('<div class="alert alert-danger">' + response.message + '</div>');
                }
                $('#alertSubmitBtn').prop('disabled', false).html('💰 Subscribe to Alert');
            },
            error: function(xhr) {
                let errorMsg = xhr.responseJSON?.message || 'Something went wrong. Please try again.';
                $('#alertMessage').html('<div class="alert alert-danger">' + errorMsg + '</div>');
                $('#alertSubmitBtn').prop('disabled', false).html('💰 Subscribe to Alert');
            }
        });
    });
});
</script>
@endpush
@endsection