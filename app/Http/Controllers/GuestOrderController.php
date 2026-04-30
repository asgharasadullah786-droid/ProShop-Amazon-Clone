<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class GuestOrderController extends Controller
{
    public function track($token)
    {
        $order = Order::where('guest_token', $token)
            ->where('is_guest', true)
            ->firstOrFail();
        
        return view('guest.order-track', compact('order'));
    }
    
    public function createAccount($token)
    {
        $order = Order::where('guest_token', $token)
            ->where('is_guest', true)
            ->firstOrFail();
        
        return view('guest.create-account', compact('order', 'token'));
    }
    
    public function registerAccount(Request $request, $token)
    {
        $order = Order::where('guest_token', $token)
            ->where('is_guest', true)
            ->firstOrFail();
        
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);
        
        // Check if user already exists
        $user = User::where('email', $order->guest_email)->first();
        
        if ($user) {
            // Link order to existing user
            $order->user_id = $user->id;
            $order->is_guest = false;
            $order->guest_token = null;
            $order->save();
            
            return redirect()->route('login')->with('success', 
                'Account already exists! Please login to view your order.');
        }
        
        // Create new user
        $user = User::create([
            'name' => $order->guest_name,
            'email' => $order->guest_email,
            'password' => bcrypt($request->password),
            'role' => 'customer'
        ]);
        
        // Link order to new user
        $order->user_id = $user->id;
        $order->is_guest = false;
        $order->guest_token = null;
        $order->save();
        
        // Login the user
        auth()->login($user);
        
        return redirect()->route('profile.orders')->with('success', 
            'Account created successfully! Your order is now linked to your account.');
    }
}