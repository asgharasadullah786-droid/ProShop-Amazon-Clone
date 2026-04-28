<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CustomerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->role !== 'customer') {
            abort(403, 'Access denied. Only customers can access this page.');
        }
        return $next($request);
    }
}