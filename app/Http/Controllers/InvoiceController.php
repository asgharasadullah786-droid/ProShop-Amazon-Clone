<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function download(Order $order)
    {
        if ($order->user_id != auth()->id() && auth()->user()->role != 'admin') {
            abort(403);
        }
        
        $pdf = PDF::loadView('invoices.order', compact('order'));
        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }
}