<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function add(Request $request)
    {
        $productId = $request->product_id;
        
        $exists = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $productId)
            ->exists();
        
        if (!$exists) {
            Wishlist::create([
                'user_id' => auth()->id(),
                'product_id' => $productId
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Added to wishlist!'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Already in wishlist!'
        ]);
    }

    public function index()
    {
        $wishlists = Wishlist::with('product')
            ->where('user_id', auth()->id())
            ->get();
        
        return view('wishlist.index', compact('wishlists'));
    }

    public function remove($id)
    {
        Wishlist::where('user_id', auth()->id())
            ->where('product_id', $id)
            ->delete();
        
        return redirect()->route('wishlist.index')->with('success', 'Removed from wishlist!');
    }
}