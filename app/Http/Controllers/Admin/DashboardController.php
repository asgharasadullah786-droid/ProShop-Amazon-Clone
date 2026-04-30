<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;  // ADD THIS
use App\Mail\OrderOTP;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        // Statistics
        $totalSales = Order::where('order_status', 'delivered')->sum('total');
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalUsers = User::count();
        $pendingOrders = Order::where('order_status', 'pending')->count();
        
        // Monthly Sales Chart
        $monthlySales = Order::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(total) as total')
        )
        ->whereYear('created_at', date('Y'))
        ->where('order_status', 'delivered')
        ->groupBy('month')
        ->orderBy('month')
        ->get();
        
        $salesData = [];
        for ($i = 1; $i <= 12; $i++) {
            $salesData[$i] = 0;
        }
        foreach ($monthlySales as $sale) {
            $salesData[$sale->month] = $sale->total;
        }
        
        // Recent Orders
        $recentOrders = Order::with('user')->latest()->limit(10)->get();
        
        // Top Products
        $topProducts = Product::withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->limit(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'totalSales', 'totalOrders', 'totalProducts', 'totalUsers', 'pendingOrders',
            'salesData', 'recentOrders', 'topProducts'
        ));
    }
    
    public function orders()
    {
        $orders = Order::with('user')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.orders', compact('orders'));
    }
    
    public function updateOrderStatus(Request $request, Order $order)
{
    $request->validate([
        'order_status' => 'required|in:pending,processing,shipped,delivered,cancelled'
    ]);
    
    // If marking as shipped for COD order, ensure OTP is generated
    if ($request->order_status == 'shipped' && $order->payment_method == 'cod') {
        if (!$order->delivery_otp) {
            $otp = $this->generateOTP();
            $order->delivery_otp = $otp;
            $order->otp_expires_at = now()->addDays(3);
            $order->save();
            
            // Send OTP email
            try {
                Mail::to($order->user->email)->send(new \App\Mail\OrderOTP($order, $otp));
            } catch (\Exception $e) {
                \Log::error('OTP Email failed: ' . $e->getMessage());
            }
        }
    }
    
    $order->order_status = $request->order_status;
    $order->save();
    
    // If cancelled, restore stock
    if ($request->order_status == 'cancelled') {
        foreach ($order->orderItems as $item) {
            $product = $item->product;
            $product->stock += $item->quantity;
            $product->save();
        }
    }
    
    return redirect()->back()->with('success', 'Order status updated!');
}

private function generateOTP()
{
    return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
}
    
    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users', compact('users'));
    }
    
    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,seller,customer'
        ]);
        
        $user->role = $request->role;
        $user->save();
        
        return redirect()->back()->with('success', 'User role updated!');
    }
    
    public function exportOrders()
    {
        $orders = Order::with('user', 'orderItems')->get();
        
        // Export to Excel/CSV
        // Implementation here
        return redirect()->back()->with('success', 'Export feature coming soon!');
    }
    public function verifyOTP(Request $request, Order $order)
{
    $request->validate([
        'otp' => 'required|string|size:6'
    ]);
    
    if ($order->payment_method !== 'cod') {
        return back()->with('error', 'OTP verification is only for COD orders.');
    }
    
    if ($order->otp_verified) {
        return back()->with('error', 'OTP already verified for this order.');
    }
    
    if ($order->otp_expires_at && now()->gt($order->otp_expires_at)) {
        return back()->with('error', 'OTP has expired. Please contact support.');
    }
    
    // Convert both to string for comparison (FIX)
    $dbOtp = (string)$order->delivery_otp;
    $inputOtp = (string)$request->otp;
    
    \Log::info('OTP Comparison - DB: ' . $dbOtp . ', Input: ' . $inputOtp);
    
    if ($dbOtp !== $inputOtp) {
        $order->increment('otp_attempts');
        $remainingAttempts = 3 - $order->otp_attempts;
        return back()->with('error', "Invalid OTP. {$remainingAttempts} attempts remaining.");
    }
    
    // OTP verified - complete delivery
    $order->otp_verified = 1;
    $order->order_status = 'delivered';
    $order->payment_status = 'paid';
    $order->delivered_at = now();
    $order->save();
    
    return redirect()->route('admin.orders')->with('success', 'OTP verified! Order marked as delivered.');
}
public function resendOTP(Order $order)
{
    if ($order->payment_method !== 'cod') {
        return back()->with('error', 'OTP is only for COD orders.');
    }
    
    if ($order->otp_verified) {
        return back()->with('error', 'Order already delivered.');
    }
    
    $otp = $this->generateOTP();
    $order->delivery_otp = $otp;
    $order->otp_expires_at = now()->addDays(3);
    $order->otp_attempts = 0;
    $order->save();
    
    try {
        Mail::to($order->user->email)->send(new \App\Mail\OrderOTP($order, $otp));
    } catch (\Exception $e) {
        \Log::error('OTP Email failed: ' . $e->getMessage());
    }
    
    return back()->with('success', 'New OTP sent to customer!');
}
}