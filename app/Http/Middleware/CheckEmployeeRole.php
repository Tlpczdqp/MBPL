<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckEmployeeRole
{
    // This middleware checks the ROLE of the logged-in employee
    // Usage: middleware('role:admin') or middleware('role:admin,manager')
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $employee = Auth::guard('employee')->user();

        // Does the employee's role match one of the allowed roles?
        if (!in_array($employee->role, $roles)) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}