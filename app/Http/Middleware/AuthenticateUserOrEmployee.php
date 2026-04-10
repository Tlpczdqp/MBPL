<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateUserOrEmployee
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('web')->check() || Auth::guard('employee')->check()) {
            return $next($request);
        }

        return redirect()->route('user.login');
    }
}