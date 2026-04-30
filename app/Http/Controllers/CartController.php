<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct()
    {
        // Remove auth middleware - allow guests to add to cart
        // $this->middleware('auth');
    }

    public function add(Request $request)
    {
        // Track abandoned cart (only for logged in users)
        if (auth()->check()) {
            app(\App\Http\Controllers\AbandonedCartController::class)->track(new \Illuminate\Http\Request());
        }
        
        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity ?? 1;
        
        // Check if this is a flash sale with custom price
        $price = $request->has('price') ? $request->price : $product->price;
        
        $cart = session()->get('cart', []);
        
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $price,
                'quantity' => $quantity,
                'image' => $product->image,
                'stock' => $product->stock,
                'original_price' => $product->price
            ];
        }
        
        session()->put('cart', $cart);
        
        return response()->json([
            'success' => true,
            'message' => 'Product added to cart at $' . number_format($price, 2) . '!',
            'cart_count' => count(session()->get('cart', []))
        ]);
    }

    // View cart
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return view('cart.index', compact('cart', 'total'));
    }

    // Update cart
    public function update(Request $request)
    {
        // Track abandoned cart (only for logged in users)
        if (auth()->check()) {
            app(\App\Http\Controllers\AbandonedCartController::class)->track(new \Illuminate\Http\Request());
        }
        
        $cart = session()->get('cart', []);
        
        if (isset($cart[$request->product_id])) {
            $cart[$request->product_id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }
        
        return response()->json(['success' => true]);
    }

    // Remove from cart
    public function remove(Request $request)
    {
        // Track abandoned cart (only for logged in users)
        if (auth()->check()) {
            app(\App\Http\Controllers\AbandonedCartController::class)->track(new \Illuminate\Http\Request());
        }
        
        $cart = session()->get('cart', []);
        
        if (isset($cart[$request->product_id])) {
            unset($cart[$request->product_id]);
            session()->put('cart', $cart);
        }
        
        return response()->json(['success' => true]);
    }

    // Clear cart
    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Cart cleared!');
    }
}