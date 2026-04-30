<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Http\Controllers\PriceAlertController;
use App\Http\Controllers\StockNotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show', 'search']);
    }

    public function index()
{
    if (auth()->check() && auth()->user()->role == 'seller') {
        $products = Product::where('user_id', auth()->id())->paginate(12);
    } else {
        $products = Product::where('status', 'active')->paginate(12);
    }
    $categories = Category::all();
    
    return view('products.index', compact('products', 'categories'));
}

    public function search(Request $request)
    {
        $query = Product::query()->where('status', 'active');
        
        // Search by keyword
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('sku', 'LIKE', "%{$searchTerm}%");
            });
        }
        
        // Filter by category
        if ($request->filled('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }
        
        // Price range filter
        if ($request->filled('min_price') && $request->min_price != '') {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price') && $request->max_price != '') {
            $query->where('price', '<=', $request->max_price);
        }
        
        // Sort by
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
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
        
        $products = $query->paginate(12);
        $categories = Category::all();
        
        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|unique:products',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name) . '-' . time();
        $product->description = $request->description;
        $product->price = $request->price;
        $product->compare_price = $request->compare_price;
        $product->stock = $request->stock;
        $product->sku = $request->sku;
        $product->category_id = $request->category_id;
        $product->user_id = auth()->id();
        $product->status = $request->status ?? 'active';

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '-main.' . $image->extension();
            $image->move(public_path('uploads/products'), $imageName);
            $product->image = 'uploads/products/' . $imageName;
        }

        $product->save();

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    public function show(Product $product)
    {
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();
            
        return view('products.show', compact('product', 'relatedProducts'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }
public function update(Request $request, Product $product)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'sku' => 'required|unique:products,sku,' . $product->id,
        'category_id' => 'required|exists:categories,id',
    ]);

    // Store old values before update
    $oldPrice = $product->price;
    $oldStock = $product->stock;
    
    // Update product
    $product->update($request->all());
    
    // Check if image was uploaded
    if ($request->hasFile('image')) {
        // Delete old image
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }
        $image = $request->file('image');
        $imageName = time() . '-main.' . $image->extension();
        $image->move(public_path('uploads/products'), $imageName);
        $product->image = 'uploads/products/' . $imageName;
        $product->save();
    }
    
    // Check for multiple images
    if ($request->hasFile('images')) {
        $images = $product->images ?? [];
        foreach ($request->file('images') as $key => $img) {
            $imgName = time() . '-' . $key . '.' . $img->extension();
            $img->move(public_path('uploads/products'), $imgName);
            $images[] = 'uploads/products/' . $imgName;
        }
        $product->images = $images;
        $product->save();
    }
    
    // Check if price dropped (Price Alert Feature)
    if ($oldPrice > $product->price) {
        \App\Http\Controllers\PriceAlertController::checkAndNotify($product);
    }
    
    // Check if stock restored from 0 to >0 (Back in Stock Feature)
    if ($oldStock <= 0 && $product->stock > 0) {
        \App\Http\Controllers\StockNotificationController::notifySubscribers($product);
    }

    return redirect()->route('products.index')->with('success', 'Product updated successfully!');
}

    public function destroy(Product $product)
    {
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }
        
        $product->delete();
        
        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}