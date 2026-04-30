<!DOCTYPE html>
<html>
<head>
    <title>Order Delivery OTP</title>
</head>
<body>
    <h1>Order Delivery OTP</h1>
    <p>Dear {{ $order->user->name }},</p>
    <p>Your order <strong>#{{ $order->order_number }}</strong> is out for delivery.</p>
    
    <div style="background: #f0f0f0; padding: 20px; text-align: center; font-size: 32px; letter-spacing: 10px;">
        <strong>{{ $otp }}</strong>
    </div>
    
    <p>Please share this OTP with the delivery person to confirm your order.</p>
    <p><strong>This OTP is valid until {{ $order->otp_expires_at->format('d M Y, h:i A') }}</strong></p>
    <p>Do not share this OTP with anyone else.</p>
    
    <hr>
    <small>ProShop - Your trusted shopping partner</small>
</body>
</html>