<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateEmployee
{
    // This middleware checks: is an EMPLOYEE logged in?
    // Uses a different Auth guard: 'employee'
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('employee')->check()) {
            return redirect()->route('employee.login')
                ->with('error', 'Please login as an employee.');
        }

        $employee = Auth::guard('employee')->user();
        
        // Deactivated employees cannot log in
        if (!$employee->is_active) {
            Auth::guard('employee')->logout();
            return redirect()->route('employee.login')
                ->with('error', 'Your account has been deactivated.');
        }

        return $next($request);
    }
}