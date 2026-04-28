<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Slider;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get all active sliders
        $sliders = Slider::where('is_active', true)->orderBy('order')->get();
        
        // Get featured products
        $featuredProducts = Product::where('status', 'active')->limit(8)->get();
        
        // Debug - check if sliders exist
        // dd($sliders); // Uncomment to debug
        
        return view('welcome', compact('sliders', 'featuredProducts'));
    }
}