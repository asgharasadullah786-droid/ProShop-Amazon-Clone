<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\OrderConfirmation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $coupon = session()->get('coupon', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }
        
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        $discount = $coupon['discount'] ?? 0;
        $total = $subtotal - $discount;
        
        return view('checkout.index', compact('cart', 'subtotal', 'discount', 'total'));
    }
    
    public function placeOrder(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'phone' => 'required|string',
            'payment_method' => 'required|in:cod,card,mobile_banking,wallet',
        ]);
        
        $cart = session()->get('cart', []);
        $coupon = session()->get('coupon', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty!');
        }
        
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        $discount = $coupon['discount'] ?? 0;
        $total = $subtotal - $discount;
        
        // Check if user is logged in or guest
        $isGuest = !auth()->check();
        $userId = auth()->check() ? auth()->id() : null;
        
        // For guests, validate email
        if ($isGuest) {
            $request->validate([
                'guest_email' => 'required|email',
                'guest_name' => 'required|string|max:255',
            ]);
        }
        
        // Create order
        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'user_id' => $userId,
            'guest_email' => $isGuest ? $request->guest_email : null,
            'guest_name' => $isGuest ? $request->guest_name : null,
            'is_guest' => $isGuest,
            'guest_token' => $isGuest ? Str::random(60) : null,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
            'payment_method' => $request->payment_method,
            'shipping_address' => $request->shipping_address,
            'phone' => $request->phone,
            'notes' => $request->notes
        ]);
        
        // Create order items
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'product_name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'total' => $item['price'] * $item['quantity']
            ]);
            
            // Reduce product stock
            $product = \App\Models\Product::find($item['id']);
            if ($product) {
                $product->stock -= $item['quantity'];
                $product->save();
            }
        }
        
        // Send order confirmation email
        $emailTo = $isGuest ? $request->guest_email : $order->user->email;
        try {
            Mail::to($emailTo)->send(new OrderConfirmation($order));
        } catch (\Exception $e) {
            \Log::error('Order confirmation email failed: ' . $e->getMessage());
        }
        
        // Clear cart and coupon
        session()->forget(['cart', 'coupon']);
        
        // Store guest token in session for order tracking
        if ($isGuest) {
            session(['guest_order_token' => $order->guest_token]);
            return redirect()->route('guest.order.track', $order->guest_token)
                ->with('success', 'Order placed successfully! A confirmation email has been sent.');
        }
        
        return redirect()->route('profile.orders')->with('success', 'Order placed successfully!');
    }
}