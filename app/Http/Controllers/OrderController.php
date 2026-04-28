<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        // Check if user owns this order or is admin
        if ($order->user_id != auth()->id() && auth()->user()->role != 'admin') {
            abort(403, 'Unauthorized access');
        }
        
        return view('orders.show', compact('order'));
    }
}