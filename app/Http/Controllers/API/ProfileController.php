<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Wishlist;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
                'created_at' => $user->created_at,
            ],
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $user->update($request->only(['name', 'phone', 'address']));
        
        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $user,
        ]);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $user = $request->user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect',
            ], 400);
        }
        
        $user->password = Hash::make($request->new_password);
        $user->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully',
        ]);
    }

    public function addresses(Request $request)
    {
        $addresses = Address::where('user_id', $request->user()->id)->get();
        
        return response()->json([
            'success' => true,
            'data' => $addresses,
        ]);
    }

    public function addAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'label' => 'required|string|max:50',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $address = Address::create([
            'user_id' => $request->user()->id,
            'label' => $request->label,
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Address added successfully',
            'data' => $address,
        ], 201);
    }

    public function deleteAddress($id)
    {
        $address = Address::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();
        
        if (!$address) {
            return response()->json([
                'success' => false,
                'message' => 'Address not found',
            ], 404);
        }
        
        $address->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Address deleted successfully',
        ]);
    }

    public function wishlist(Request $request)
    {
        $wishlist = Wishlist::with('product')
            ->where('user_id', $request->user()->id)
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $wishlist,
        ]);
    }

    public function addToWishlist($id)
    {
        $exists = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $id)
            ->exists();
        
        if (!$exists) {
            Wishlist::create([
                'user_id' => auth()->id(),
                'product_id' => $id,
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Added to wishlist',
        ]);
    }

    public function removeFromWishlist($id)
    {
        Wishlist::where('user_id', auth()->id())
            ->where('product_id', $id)
            ->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Removed from wishlist',
        ]);
    }

    public function wallet(Request $request)
    {
        $wallet = Wallet::where('user_id', $request->user()->id)->first();
        
        return response()->json([
            'success' => true,
            'data' => [
                'balance' => $wallet ? number_format($wallet->balance, 2) : '0.00',
                'transactions' => $wallet ? $wallet->transactions()->latest()->limit(20)->get() : [],
            ],
        ]);
    }

    public function addWalletBalance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:100|max:50000',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $request->user()->id],
            ['balance' => 0]
        );
        
        $wallet->credit($request->amount, 'Mobile app recharge');
        
        return response()->json([
            'success' => true,
            'message' => 'Balance added successfully',
            'balance' => number_format($wallet->balance, 2),
        ]);
    }
}