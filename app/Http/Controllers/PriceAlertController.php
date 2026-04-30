<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PriceAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PriceDropAlert;

class PriceAlertController extends Controller
{
    public function subscribe(Request $request, Product $product)
    {
        $request->validate([
            'email' => 'required|email',
            'desired_price' => 'required|numeric|min:0|max:' . $product->price,
            'name' => 'nullable|string|max:255'
        ]);

        // Check if already subscribed
        $existing = PriceAlert::where('product_id', $product->id)
            ->where('email', $request->email)
            ->where('is_sent', false)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'You are already subscribed to price alerts for this product!'
            ]);
        }

        // Create subscription
        PriceAlert::create([
            'user_id' => auth()->check() ? auth()->id() : null,
            'product_id' => $product->id,
            'email' => $request->email,
            'name' => $request->name,
            'desired_price' => $request->desired_price,
            'current_price' => $product->price,
            'unsubscribe_token' => PriceAlert::generateToken()
        ]);

        return response()->json([
            'success' => true,
            'message' => "We'll notify you when price drops below $" . number_format($request->desired_price, 2)
        ]);
    }

    public function unsubscribe($token)
    {
        $alert = PriceAlert::where('unsubscribe_token', $token)->firstOrFail();
        $productName = $alert->product->name;
        $alert->delete();

        return view('price-alert.unsubscribed', compact('productName'));
    }

    // Called when product price is updated
    public static function checkAndNotify(Product $product)
    {
        $alerts = PriceAlert::where('product_id', $product->id)
            ->where('is_sent', false)
            ->where('desired_price', '>=', $product->price)
            ->get();

        foreach ($alerts as $alert) {
            try {
                Mail::to($alert->email)->send(new PriceDropAlert($product, $alert));
                $alert->update([
                    'is_sent' => true,
                    'sent_at' => now()
                ]);
            } catch (\Exception $e) {
                \Log::error('Price drop email failed: ' . $e->getMessage());
            }
        }
    }
}