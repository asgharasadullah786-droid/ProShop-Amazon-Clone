<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\Order;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('customer'); // Only customers can use wallet
    }

    public function index()
    {
        $wallet = auth()->user()->wallet;
        
        if (!$wallet) {
            $wallet = Wallet::create([
                'user_id' => auth()->id(),
                'balance' => 0
            ]);
        }
        
        $transactions = auth()->user()->walletTransactions()->latest()->paginate(20);
        
        return view('wallet.index', compact('wallet', 'transactions'));
    }

    public function addBalance(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100|max:50000',
        ]);

        $amount = $request->amount;
        
        // Store in session for payment
        session(['wallet_recharge_amount' => $amount]);
        
        return redirect()->route('wallet.payment');
    }

    public function payment()
    {
        $amount = session('wallet_recharge_amount');
        
        if (!$amount) {
            return redirect()->route('wallet.index')->with('error', 'Please enter amount first.');
        }
        
        return view('wallet.payment', compact('amount'));
    }

    public function paymentSuccess(Request $request)
    {
        $amount = session('wallet_recharge_amount');
        
        if (!$amount) {
            return redirect()->route('wallet.index')->with('error', 'Invalid request.');
        }
        
        $wallet = auth()->user()->wallet;
        
        if (!$wallet) {
            $wallet = Wallet::create([
                'user_id' => auth()->id(),
                'balance' => 0
            ]);
        }
        
        // Add balance to wallet
        $wallet->credit(
            $amount, 
            'Wallet recharge via ' . ucfirst($request->payment_method),
            'RECHARGE_' . time()
        );
        
        session()->forget('wallet_recharge_amount');
        
        return redirect()->route('wallet.index')->with('success', 'Wallet recharged successfully! Current balance: $' . number_format($wallet->balance, 2));
    }

    public function useWalletForOrder(Order $order)
    {
        $wallet = auth()->user()->wallet;
        
        if (!$wallet || !$wallet->hasSufficientBalance($order->total)) {
            return redirect()->route('checkout.index')->with('error', 'Insufficient wallet balance!');
        }
        
        // Debit from wallet
        $wallet->debit(
            $order->total,
            'Payment for order #' . $order->order_number,
            $order->order_number,
            $order->id
        );
        
        // Update order payment status
        $order->payment_status = 'paid';
        $order->payment_method = 'wallet';
        $order->save();
        
        session()->forget(['cart', 'coupon']);
        
        return redirect()->route('profile.orders')->with('success', 'Order placed successfully using wallet!');
    }
}