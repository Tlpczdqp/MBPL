<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeLoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('employee')->check()) {
            return redirect()->route('employee.dashboard');
        }
        return view('auth.employee.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // Use the 'employee' guard specifically
        if (Auth::guard('employee')->attempt([
            'email'    => $request->email,
            'password' => $request->password,
        ], $request->boolean('remember'))) {

            $employee = Auth::guard('employee')->user();

            if (!$employee->is_active) {
                Auth::guard('employee')->logout();
                return back()->withErrors(['email' => 'Your account has been deactivated.']);
            }

            $request->session()->regenerate();
            return redirect()->route('employee.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])
            ->withInput($request->only('email'));
    }
}