<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BackInStockMail;

class StockNotificationController extends Controller
{
    public function subscribe(Request $request, Product $product)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'nullable|string|max:255'
        ]);

        // Check if already subscribed
        $existing = StockNotification::where('product_id', $product->id)
            ->where('email', $request->email)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'You are already subscribed to this product!'
            ]);
        }

        // Create subscription
        StockNotification::create([
            'product_id' => $product->id,
            'email' => $request->email,
            'name' => $request->name,
            'unsubscribe_token' => StockNotification::generateToken()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'We will notify you when this product is back in stock!'
        ]);
    }

    public function unsubscribe($token)
    {
        $notification = StockNotification::where('unsubscribe_token', $token)->firstOrFail();
        $productName = $notification->product->name;
        $notification->delete();

        return view('stock-notification.unsubscribed', compact('productName'));
    }

    // Called when product stock is updated
    public static function notifySubscribers(Product $product)
    {
        if ($product->stock <= 0) {
            return;
        }

        $notifications = StockNotification::where('product_id', $product->id)
            ->where('is_sent', false)
            ->get();

        foreach ($notifications as $notification) {
            try {
                Mail::to($notification->email)->send(new BackInStockMail($product, $notification));
                $notification->update([
                    'is_sent' => true,
                    'sent_at' => now()
                ]);
            } catch (\Exception $e) {
                \Log::error('Back in stock email failed: ' . $e->getMessage());
            }
        }
    }
}