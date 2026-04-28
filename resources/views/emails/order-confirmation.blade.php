<h1>Order Confirmation</h1>
<p>Dear {{ $order->user->name }},</p>
<p>Thank you for your order! Your order #{{ $order->order_number }} has been placed successfully.</p>
<p>Total Amount: ${{ number_format($order->total, 2) }}</p>
<a href="{{ route('profile.order-details', $order->id) }}">View Order Details</a>