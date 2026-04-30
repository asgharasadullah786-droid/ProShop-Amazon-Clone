<!DOCTYPE html>
<html>
<head>
    <title>Price Drop Alert</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #dc3545; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .price-box { background: #f8f9fa; padding: 15px; margin: 20px 0; text-align: center; }
        .old-price { text-decoration: line-through; color: #999; font-size: 18px; }
        .new-price { color: #dc3545; font-size: 28px; font-weight: bold; }
        .btn { display: inline-block; padding: 12px 30px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; }
        .unsubscribe { font-size: 12px; color: #999; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💰 Price Drop Alert!</h1>
        </div>
        
        <div class="content">
            <h2>Good News, {{ $alert->name ?? 'Valued Customer' }}!</h2>
            
            <p>The product you were waiting for has dropped in price!</p>
            
            <div class="price-box">
                <h3>{{ $product->name }}</h3>
                <div class="old-price">Was: ${{ number_format($alert->current_price, 2) }}</div>
                <div class="new-price">Now: ${{ number_format($product->price, 2) }}</div>
                <p>You wanted it at ${{ number_format($alert->desired_price, 2) }} or below.</p>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ route('products.show', $product) }}" class="btn">
                    🛒 Shop Now
                </a>
            </div>
            
            <div class="unsubscribe">
                <a href="{{ route('price-alert.unsubscribe', $alert->unsubscribe_token) }}">
                    Unsubscribe from this price alert
                </a>
            </div>
        </div>
        
        <div class="footer">
            <p>ProShop - Your trusted shopping partner</p>
        </div>
    </div>
</body>
</html>