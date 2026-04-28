<?php

namespace App\Http\Controllers;

use App\Models\FlashSale;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FlashSaleController extends Controller
{
    public function index()
    {
        $now = Carbon::now('Asia/Karachi');
        
        $flashSales = FlashSale::with('product')
            ->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('flash-sales.index', compact('flashSales'));
    }
    
    public function adminIndex()
    {
        $flashSales = FlashSale::with('product')->orderBy('created_at', 'desc')->paginate(10);
        $products = Product::where('status', 'active')->get();
        
        $now = Carbon::now('Asia/Karachi');
        foreach ($flashSales as $flashSale) {
            $flashSale->isCurrentlyActive = $now->between($flashSale->start_time, $flashSale->end_time);
        }
        
        return view('admin.flash-sales', compact('flashSales', 'products'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'sale_price' => 'required|numeric|min:0',
            'sale_quantity' => 'required|integer|min:0',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);
        
        FlashSale::create([
            'product_id' => $request->product_id,
            'sale_price' => $request->sale_price,
            'sale_quantity' => $request->sale_quantity,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);
        
        return redirect()->back()->with('success', 'Flash sale created successfully!');
    }
    
    public function destroy(FlashSale $flashSale)
    {
        $flashSale->delete();
        return redirect()->back()->with('success', 'Flash sale deleted!');
    }
    
    public function addToCart(Request $request)
    {
        $flashSale = FlashSale::findOrFail($request->flash_sale_id);
        $product = $flashSale->product;
        $quantity = $request->quantity ?? 1;
        
        $cart = session()->get('cart', []);
        
        // Use product ID as key (override regular cart item)
        $cart[$product->id] = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $flashSale->sale_price,
            'quantity' => $quantity,
            'image' => $product->image,
            'is_flash_sale' => true,
            'flash_sale_id' => $flashSale->id,
            'original_price' => $product->price
        ];
        
        session()->put('cart', $cart);
        
        return redirect()->route('cart.index')->with('success', 'Flash sale item added to cart at $' . number_format($flashSale->sale_price, 2) . '!');
    }
}