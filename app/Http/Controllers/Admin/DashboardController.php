<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        
        $order->updateStatus($request->order_status);
        
        return redirect()->back()->with('success', 'Order status updated!');
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
}