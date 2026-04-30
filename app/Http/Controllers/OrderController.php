<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        // Check if user owns this order or is admin
        if ($order->user_id != auth()->id() && auth()->user()->role != 'admin') {
            abort(403, 'Unauthorized access');
        }
        
        return view('orders.show', compact('order'));
    }
    public function cancel(Request $request, Order $order)
{
    // Check if user owns this order
    if ($order->user_id != auth()->id()) {
        abort(403, 'Unauthorized action.');
    }
    
    // Only pending or processing orders can be cancelled
    if (!in_array($order->order_status, ['pending', 'processing'])) {
        return back()->with('error', 'This order cannot be cancelled.');
    }
    
    // Update order status
    $order->order_status = 'cancelled';
    $order->cancelled_at = now();
    $order->save();
    
    // Restore product stock
    foreach ($order->orderItems as $item) {
        $product = $item->product;
        if ($product) {
            $product->stock += $item->quantity;
            $product->save();
        }
    }
    
    // Send cancellation email (optional)
    // Mail::to($order->user->email)->send(new OrderCancelled($order));
    
    return redirect()->route('profile.orders')->with('success', 'Order cancelled successfully!');
}
}