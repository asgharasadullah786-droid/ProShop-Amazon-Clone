<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $user->update($request->only('name', 'email', 'phone', 'address'));

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully!');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password is incorrect!');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password changed successfully!');
    }

    public function orders()
{
    if (auth()->user()->role == 'admin') {
        // Admin can see all orders
        $orders = Order::orderBy('created_at', 'desc')->paginate(10);
    } else {
        // Customer sees only their orders
        $orders = Order::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }
    
    return view('profile.orders', compact('orders'));
}

    public function orderDetails($id)
{
    if (auth()->user()->role == 'admin') {
        $order = Order::with('orderItems')->findOrFail($id);
    } else {
        $order = Order::where('user_id', auth()->id())
            ->with('orderItems')
            ->findOrFail($id);
    }
    
    return view('profile.order_details', compact('order'));
}
}