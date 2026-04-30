<!DOCTYPE html>
<html>
<head>
    <title>Back in Stock Notification</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #28a745; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .product-box { background: #f8f9fa; padding: 15px; margin: 20px 0; text-align: center; }
        .btn { display: inline-block; padding: 12px 30px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
        .unsubscribe { font-size: 12px; color: #999; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✓ Back in Stock!</h1>
        </div>
        
        <div class="content">
            <h2>Good News, {{ $notification->name ?? 'Valued Customer' }}!</h2>
            
            <p>The product you were waiting for is now back in stock!</p>
            
            <div class="product-box">
                <h3>{{ $product->name }}</h3>
                <p><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
                <p><strong>Available Stock:</strong> {{ $product->stock }} items</p>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ route('products.show', $product) }}" class="btn">
                    🛒 Buy Now
                </a>
            </div>
            
            <div class="unsubscribe">
                <a href="{{ route('stock.unsubscribe', $notification->unsubscribe_token) }}">
                    Unsubscribe from this notification
                </a>
            </div>
        </div>
        
        <div class="footer">
            <p>ProShop - Your trusted shopping partner</p>
        </div>
    </div>
</body>
</html>