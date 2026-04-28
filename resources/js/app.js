import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
// Add to Cart
$(document).on('click', '.add-to-cart', function() {
    let productId = $(this).data('id');
    let quantity = $('#quantity').val() || 1;
    
    $.ajax({
        url: '/cart/add',
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            product_id: productId,
            quantity: quantity
        },
        success: function(response) {
            $('#cart-count').text(response.cart_count);
            alert(response.message);
        }
    });
});

// Add to Wishlist
$(document).on('click', '.add-to-wishlist', function() {
    let productId = $(this).data('id');
    
    $.ajax({
        url: '/wishlist/add',
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            product_id: productId
        },
        success: function(response) {
            alert(response.message);
        }
    });
});

// Apply Coupon
$('#apply-coupon').click(function() {
    let code = $('#coupon_code').val();
    
    $.ajax({
        url: '/coupon/apply',
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            code: code
        },
        success: function(response) {
            if(response.success) {
                $('#discount-row').show();
                $('#discount-amount').text(response.discount);
                $('#total').text(response.total);
                $('#coupon-message').html('<div class="alert alert-success">' + response.message + '</div>');
            } else {
                $('#coupon-message').html('<div class="alert alert-danger">' + response.message + '</div>');
            }
        }
    });
});