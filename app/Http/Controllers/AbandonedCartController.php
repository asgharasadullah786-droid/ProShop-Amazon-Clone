<?php

namespace App\Http\Controllers;

use App\Models\AbandonedCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AbandonedCartReminder;

class AbandonedCartController extends Controller
{
    public function track(Request $request)
    {
        $sessionId = session()->getId();
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            // Cart is empty, delete abandoned record
            AbandonedCart::where('session_id', $sessionId)->delete();
            return;
        }
        
        // Calculate cart total and item count
        $total = 0;
        $itemCount = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
            $itemCount += $item['quantity'];
        }
        
        // Find existing abandoned cart
        $abandonedCart = AbandonedCart::where('session_id', $sessionId)->first();
        
        if ($abandonedCart) {
            // Update existing
            $abandonedCart->update([
                'cart_data' => $cart,
                'cart_total' => $total,
                'item_count' => $itemCount,
                'last_activity' => now(),
                'user_id' => auth()->id(),
                'email' => auth()->check() ? auth()->user()->email : $abandonedCart->email,
                'name' => auth()->check() ? auth()->user()->name : $abandonedCart->name,
            ]);
        } else {
            // Create new
            AbandonedCart::create([
                'session_id' => $sessionId,
                'cart_data' => $cart,
                'cart_total' => $total,
                'item_count' => $itemCount,
                'last_activity' => now(),
                'user_id' => auth()->id(),
                'email' => auth()->check() ? auth()->user()->email : null,
                'name' => auth()->check() ? auth()->user()->name : null,
            ]);
        }
    }
    
    public function sendReminders()
    {
        // This method should be called by cron job
        $abandonedCarts = AbandonedCart::where('reminder_sent', false)
            ->where('recovered', false)
            ->where('last_activity', '<=', now()->subHours(1))
            ->whereNotNull('email')
            ->get();
        
        $sent = 0;
        foreach ($abandonedCarts as $abandonedCart) {
            try {
                Mail::to($abandonedCart->email)->send(new AbandonedCartReminder($abandonedCart));
                $abandonedCart->update([
                    'reminder_sent' => true,
                    'reminder_sent_at' => now()
                ]);
                $sent++;
            } catch (\Exception $e) {
                \Log::error('Abandoned cart email failed: ' . $e->getMessage());
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "Sent {$sent} reminders"
        ]);
    }
    
    public function recover(Request $request, $id)
    {
        $abandonedCart = AbandonedCart::findOrFail($id);
        $abandonedCart->update(['recovered' => true]);
        
        // Restore cart to session
        session()->put('cart', $abandonedCart->cart_data);
        
        return redirect()->route('cart.index')->with('success', 'Your cart has been restored!');
    }
}