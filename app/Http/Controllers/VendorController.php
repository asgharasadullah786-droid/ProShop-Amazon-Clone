<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function storefront($id)
{
    $user = User::find($id);
    
    if (!$user) {
        abort(404, 'User not found.');
    }
    
    // Allow both sellers and admins to have store view
    if ($user->role !== 'seller' && $user->role !== 'admin') {
        abort(404, 'This user does not have a store.');
    }
    
    $products = Product::where('user_id', $user->id)
        ->where('status', 'active')
        ->paginate(12);
    
    $totalProducts = Product::where('user_id', $user->id)->count();
    $totalSold = $user->sold_count ?? 0;
    
    // Calculate total revenue from delivered orders
    $totalRevenue = 0;
    foreach ($user->products as $product) {
        foreach ($product->orderItems as $item) {
            if ($item->order && $item->order->order_status == 'delivered') {
                $totalRevenue += $item->total;
            }
        }
    }
    
    return view('vendor.storefront', compact('user', 'products', 'totalProducts', 'totalSold', 'totalRevenue'));
}
 public function edit()
{
    $user = auth()->user();
    
    // Allow both sellers and admins to edit their store
    if ($user->role !== 'seller' && $user->role !== 'admin') {
        abort(403, 'Only sellers and admins can edit store.');
    }
    
    return view('vendor.edit', compact('user'));
}

public function update(Request $request)
{
    $user = auth()->user();
    
    // Allow both sellers and admins to edit their store
    if ($user->role !== 'seller' && $user->role !== 'admin') {
        abort(403, 'Only sellers and admins can edit store.');
    }
    
    $request->validate([
        'store_description' => 'nullable|string|max:1000',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);
    
    if ($request->hasFile('avatar')) {
        $avatar = $request->file('avatar');
        $avatarName = time() . '.' . $avatar->extension();
        $avatar->move(public_path('uploads/avatars'), $avatarName);
        $user->avatar = 'uploads/avatars/' . $avatarName;
    }
    
    $user->store_description = $request->store_description;
    $user->save();
    
    return redirect()->route('vendor.storefront', $user->id)->with('success', 'Store updated successfully!');
}
}