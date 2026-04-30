<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\FlashSale;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'user'])->where('status', 'active');
        
        // Search
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        // Category filter
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        // Price range
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        
        // Sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }
        
        $products = $query->paginate($request->per_page ?? 15);
        
        // Add discount and rating to each product
        foreach ($products as $product) {
            $product->discount_percent = $product->discount_percent;
            $product->average_rating = $product->average_rating;
        }
        
        return response()->json([
            'success' => true,
            'data' => $products,
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ]);
    }

    public function show($id)
    {
        $product = Product::with(['category', 'user', 'reviews' => function($q) {
            $q->where('is_approved', true);
        }])->find($id);
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }
        
        $product->discount_percent = $product->discount_percent;
        $product->average_rating = $product->average_rating;
        
        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }

    public function categories()
    {
        $categories = Category::withCount('products')->get();
        
        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    public function flashSales()
    {
        $flashSales = FlashSale::with('product')
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $flashSales,
        ]);
    }
}