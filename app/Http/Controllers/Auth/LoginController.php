<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        Log::info('=== LOGIN DEBUG ===');
        Log::info('User ID: ' . $user->id);
        Log::info('User Email: ' . $user->email);
        Log::info('User Role: ' . $user->role);
        
        if ($user->role === 'admin') {
            Log::info('Redirecting to /admin/dashboard');
            return redirect('/admin/dashboard');
        } elseif ($user->role === 'seller') {
            Log::info('Redirecting to /store/' . $user->id);
            return redirect('/store/' . $user->id);
        }
        
        Log::info('Redirecting to /home');
        return redirect('/home');
    }
}