<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:3',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Check if user already reviewed this product
        $existingReview = Review::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'You already reviewed this product!');
        }

        // Handle image uploads
        $imagePaths = [];
        $hasImages = false;
        
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/reviews'), $filename);
                $imagePaths[] = $filename;
            }
            $hasImages = true;
        }

        Review::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'images' => $imagePaths,
            'has_images' => $hasImages,
            'is_approved' => true
        ]);

        return redirect()->back()->with('success', 'Review submitted successfully!');
    }

    public function destroy(Review $review)
    {
        // Only admin or review owner can delete
        if (auth()->user()->role !== 'admin' && auth()->id() !== $review->user_id) {
            abort(403);
        }
        
        // Delete image files
        if ($review->has_images && $review->images) {
            foreach ($review->images as $image) {
                $imagePath = public_path('uploads/reviews/' . $image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        }
        
        $review->delete();
        
        return redirect()->back()->with('success', 'Review deleted successfully!');
    }

    public function toggleApproval(Review $review)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
        
        $review->is_approved = !$review->is_approved;
        $review->save();
        
        return redirect()->back()->with('success', 'Review approval status updated!');
    }
}