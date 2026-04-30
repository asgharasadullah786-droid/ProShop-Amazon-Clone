<!DOCTYPE html>
<html>
<head>
    <title>Complete Your Order - ProShop</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; padding: 20px; background: #0d6efd; color: white; }
        .content { padding: 20px; }
        .cart-items { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .cart-items th, .cart-items td { padding: 10px; border-bottom: 1px solid #ddd; text-align: left; }
        .discount-box { background: #f8f9fa; padding: 15px; text-align: center; margin: 20px 0; border-radius: 5px; }
        .discount-code { font-size: 24px; font-weight: bold; color: #dc3545; letter-spacing: 2px; }
        .btn { display: inline-block; padding: 12px 30px; background: #0d6efd; color: white; text-decoration: none; border-radius: 5px; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #999; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🛒 ProShop</h1>
            <p>Your cart is waiting for you!</p>
        </div>
        
        <div class="content">
            <h2>Hello {{ $abandonedCart->name ?? 'Valued Customer' }},</h2>
            
            <p>You left some items in your cart. We saved them for you!</p>
            
            <h3>Your Cart Summary:</h3>
            <table class="cart-items">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($abandonedCart->cart_data as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2">Total:</th>
                        <th>${{ number_format($abandonedCart->cart_total, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
            
            <div class="discount-box">
                <p><strong>🎁 Special Offer Just for You!</strong></p>
                <p>Complete your purchase within 24 hours and get 10% OFF!</p>
                <div class="discount-code">{{ $discountCode }}</div>
                <small>Use this code at checkout</small>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('cart.index') }}" class="btn">🛒 Complete Your Order</a>
            </div>
            
            <p><small>This offer expires in 24 hours. Don't miss out!</small></p>
            <p>Questions? Contact us at support@proshop.com</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} ProShop. All rights reserved.</p>
            <p>This email was sent because you left items in your cart.</p>
        </div>
    </div>
</body>
</html>