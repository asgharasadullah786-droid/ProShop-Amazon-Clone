<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $compareIds = session()->get('compare', []);
        $products = Product::whereIn('id', $compareIds)->get();
        
        return view('compare.index', compact('products'));
    }
    
    public function add(Request $request)
    {
        $productId = $request->product_id;
        $compare = session()->get('compare', []);
        
        if (!in_array($productId, $compare)) {
            if (count($compare) >= 4) {
                return response()->json([
                    'success' => false, 
                    'message' => 'You can compare up to 4 products only!'
                ]);
            }
            $compare[] = $productId;
            session()->put('compare', $compare);
            
            return response()->json([
                'success' => true, 
                'message' => 'Product added to compare!'
            ]);
        }
        
        return response()->json([
            'success' => false, 
            'message' => 'Product already in compare list!'
        ]);
    }
    
    public function remove(Request $request)
    {
        $compare = session()->get('compare', []);
        $key = array_search($request->product_id, $compare);
        
        if ($key !== false) {
            unset($compare[$key]);
            session()->put('compare', array_values($compare));
        }
        
        return redirect()->route('compare.index')->with('success', 'Product removed from compare!');
    }
    
    public function clear()
    {
        session()->forget('compare');
        return redirect()->route('compare.index')->with('success', 'Compare list cleared!');
    }
}