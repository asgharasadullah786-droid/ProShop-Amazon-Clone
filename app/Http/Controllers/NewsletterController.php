<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        
        // You can save to database or send to Mailchimp
        // For now, just return success
        
        return response()->json([
            'success' => true,
            'message' => 'Subscribed successfully!'
        ]);
    }
}