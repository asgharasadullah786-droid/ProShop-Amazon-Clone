<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class ApiDocsController extends Controller
{
    public function index()
    {
        return response()->json([
            'app_name' => 'ProShop API',
            'version' => '1.0.0',
            'base_url' => url('/api'),
            'endpoints' => $this->getEndpoints(),
            'authentication' => [
                'type' => 'Bearer Token',
                'how_to_get' => 'Use /api/login or /api/register endpoints',
                'header' => 'Authorization: Bearer {your_token}',
            ],
        ]);
    }

    private function getEndpoints()
    {
        return [
            'public_endpoints' => [
                'POST /api/register' => 'Register new user',
                'POST /api/login' => 'Login user',
                'GET /api/products' => 'Get all products',
                'GET /api/products/{id}' => 'Get single product',
                'GET /api/categories' => 'Get all categories',
                'GET /api/flash-sales' => 'Get active flash sales',
            ],
            'protected_endpoints_auth_required' => [
                'POST /api/logout' => 'Logout',
                'GET /api/user' => 'Get user profile',
                'GET /api/cart' => 'Get cart items',
                'POST /api/cart/add' => 'Add to cart',
                'GET /api/orders' => 'Get orders',
                'POST /api/orders' => 'Create order',
                'GET /api/profile' => 'Get profile',
                'PUT /api/profile' => 'Update profile',
                'GET /api/addresses' => 'Get addresses',
                'POST /api/addresses' => 'Add address',
                'GET /api/wishlist' => 'Get wishlist',
                'GET /api/wallet' => 'Get wallet balance',
            ],
        ];
    }
}