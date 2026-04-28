<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Mail\OrderConfirmation;
use Illuminate\Support\Facades\Mail;

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
            'payment_method' => 'required|in:cod,card,mobile_banking'
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
        
        // Create order
        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'user_id' => auth()->id(),
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

        // After order creation
        Mail::to($order->user->email)->send(new OrderConfirmation($order));

        // Clear cart and coupon
        session()->forget(['cart', 'coupon']);
        
        return redirect()->route('products.index')->with('success', 'Order placed successfully!');
    }
}