<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateUser
{
    // This middleware checks: is a USER logged in?
    // If not, redirect to login page
    public function handle(Request $request, Closure $next)
    {
        // 'web' is the default guard for users
        if (!Auth::guard('web')->check()) {
            return redirect()->route('user.login')
                ->with('error', 'Please login to continue.');
        }

        // Check if email is verified
        $user = Auth::guard('web')->user();
        if (!$user->email_verified) {
            Auth::guard('web')->logout();
            return redirect()->route('user.login')
                ->with('error', 'Please verify your email first.');
        }

        return $next($request);
    }
}