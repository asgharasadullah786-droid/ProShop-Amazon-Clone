<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $coupon = Coupon::where('code', $request->code)
            ->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', Carbon::now());
            })
            ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired coupon!'
            ]);
        }

        // Check usage limit
        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon usage limit reached!'
            ]);
        }

        $cart = session()->get('cart', []);
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // Check minimum cart amount
        if ($coupon->min_cart_amount && $subtotal < $coupon->min_cart_amount) {
            return response()->json([
                'success' => false,
                'message' => "Minimum cart amount should be \${$coupon->min_cart_amount}"
            ]);
        }

        // Calculate discount
        if ($coupon->type == 'fixed') {
            $discount = min($coupon->value, $subtotal);
        } else {
            $discount = ($subtotal * $coupon->value) / 100;
        }

        session()->put('coupon', [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'discount' => $discount
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully!',
            'discount' => $discount,
            'total' => $subtotal - $discount
        ]);
    }

    public function remove(Request $request)
    {
        session()->forget('coupon');
        
        return response()->json([
            'success' => true,
            'message' => 'Coupon removed!'
        ]);
    }
}