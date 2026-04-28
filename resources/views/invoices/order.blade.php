<!DOCTYPE html>
<html>
<head>
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 30px; }
        .order-info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total { text-align: right; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>ProShop Invoice</h1>
        <p>Order #{{ $order->order_number }}</p>
    </div>
    
    <div class="order-info">
        <p><strong>Order Date:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
        <p><strong>Customer Name:</strong> {{ $order->user->name }}</p>
        <p><strong>Shipping Address:</strong> {{ $order->shipping_address }}</p>
        <p><strong>Phone:</strong> {{ $order->phone }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
            <tr>
                <td>{{ $item->product_name }}</td>
                <td>${{ number_format($item->price, 2) }}</td>
                <td>{{ $item->quantity }}</td>
                <td>${{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-end">Subtotal:</th>
                <td>${{ number_format($order->subtotal, 2) }}</td>
            </tr>
            @if($order->discount > 0)
            <tr>
                <th colspan="3" class="text-end">Discount:</th>
                <td>-${{ number_format($order->discount, 2) }}</td>
            </tr>
            @endif
            <tr>
                <th colspan="3" class="text-end">Total:</th>
                <td><strong>${{ number_format($order->total, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>
    
    <div class="total">
        <p>Thank you for shopping with ProShop!</p>
    </div>
</body>
</html>