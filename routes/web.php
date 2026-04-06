<?php

use App\Http\Controllers\Auth\UserLoginController;
use App\Http\Controllers\Employee\ManagerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\BusinessApplicationController;
use App\Http\Controllers\User\UserDashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ============================================================
// PUBLIC ROUTES (no login required)
// ============================================================

// Home page — redirects to user login
Route::get('/', function () {
    return redirect()->route('user.login');
});

// ── USER AUTH ───────────────────────────────────────────────
Route::prefix('user')->name('user.')->group(function () {

    // Login
    Route::get('/login', [UserLoginController::class, 'showLogin'])
        ->name('login');
    Route::post('/login', [UserLoginController::class, 'login'])
        ->name('login.submit');

    // Register
    Route::get('/register', [App\Http\Controllers\Auth\UserRegisterController::class, 'showRegister'])
        ->name('register');
    Route::post('/register', [App\Http\Controllers\Auth\UserRegisterController::class, 'register'])
        ->name('register.submit');

    // OTP Verification
    Route::get('/verify-otp', [App\Http\Controllers\Auth\UserOtpController::class, 'showOtp'])
        ->name('otp.show');
    Route::post('/verify-otp', [App\Http\Controllers\Auth\UserOtpController::class, 'verifyOtp'])
        ->name('otp.verify');
    Route::post('/resend-otp', [App\Http\Controllers\Auth\UserOtpController::class, 'resendOtp'])
        ->name('otp.resend');

    // Forgot Password
    Route::get('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showForgotForm'])
        ->name('password.request');
    Route::post('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLink'])
        ->name('password.email');
    Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showResetForm'])
        ->name('password.reset');
    Route::post('/reset-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'resetPassword'])
        ->name('password.update');
});

// Social Auth (Google & Facebook) — users only
Route::get('/auth/google', [App\Http\Controllers\Auth\SocialAuthController::class, 'redirectToGoogle'])
    ->name('auth.google');
Route::get('/auth/google/callback', [App\Http\Controllers\Auth\SocialAuthController::class, 'handleGoogleCallback'])
    ->name('auth.google.callback');
Route::get('/auth/facebook', [App\Http\Controllers\Auth\SocialAuthController::class, 'redirectToFacebook'])
    ->name('auth.facebook');
Route::get('/auth/facebook/callback', [App\Http\Controllers\Auth\SocialAuthController::class, 'handleFacebookCallback'])
    ->name('auth.facebook.callback');

// ── EMPLOYEE AUTH ────────────────────────────────────────────
Route::prefix('employee')->name('employee.')->group(function () {
    Route::get('/login', [App\Http\Controllers\Auth\EmployeeLoginController::class, 'showLogin'])
        ->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\EmployeeLoginController::class, 'login'])
        ->name('login.submit');
});

// ── LOGOUT ──────────────────────────────────────────────────
Route::post('/logout', function () {

    // Check if an employee is currently logged in
    // Auth::guard('employee')->check() uses StatefulGuard → works correctly
    if (Auth::guard('employee')->check()) {

        // Auth::guard('employee')->logout() calls StatefulGuard::logout()
        // This clears the employee session
        Auth::guard('employee')->logout();

        // Invalidate the entire session (removes all session data)
        request()->session()->invalidate();

        // Regenerate the CSRF token so old tokens cannot be reused
        request()->session()->regenerateToken();

        return redirect()->route('employee.login')
            ->with('success', 'You have been logged out.');
    }

    // Otherwise log out the regular user using the default 'web' guard
    // Auth::logout() is shorthand for Auth::guard('web')->logout()
    Auth::logout();

    // Invalidate and regenerate session
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('user.login')
        ->with('success', 'You have been logged out.');

})->name('logout');

// ============================================================
// USER ROUTES (must be logged in as USER)
// ============================================================
Route::prefix('user/{userId}')
    ->name('user.')
    ->middleware('auth.user')
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', [UserDashboardController::class, 'index'])
        ->name('dashboard');

    // Business Applications
    Route::get('/applications', [BusinessApplicationController::class, 'index'])
        ->name('business.index');
    Route::get('/applications/create', [BusinessApplicationController::class, 'create'])
        ->name('business.create');
    Route::post('/applications', [BusinessApplicationController::class, 'store'])
        ->name('business.store');
    Route::get('/applications/{application}', [BusinessApplicationController::class, 'show'])
        ->name('business.show');
        
     Route::get('/applications/{application}', [BusinessApplicationController::class, 'show'])
        ->name('business.show');

    // ✅ DELETE application (only pending/rejected)
    Route::delete('/applications/{application}', [BusinessApplicationController::class, 'destroy'])
        ->name('business.destroy');

    // ✅ VIEW document file
    Route::get('/applications/{application}/document/{document}/view', [BusinessApplicationController::class, 'viewDocument'])
        ->name('business.document.view');


    // Renew Business Permit
    Route::get('/applications/{application}/renew', [App\Http\Controllers\User\BusinessApplicationController::class, 'renew'])
        ->name('business.renew');
    Route::post('/applications/{application}/renew', [App\Http\Controllers\User\BusinessApplicationController::class, 'storeRenewal'])
        ->name('business.renew.store');

    // Payment
    Route::get('/applications/{application}/pay', [App\Http\Controllers\User\PaymentController::class, 'showPayment'])
        ->name('payment.show');
    Route::post('/applications/{application}/pay', [App\Http\Controllers\User\PaymentController::class, 'submitPayment'])
        ->name('payment.submit');

    // Print Permit (only if permit_issued)
    Route::get('/applications/{application}/print', [App\Http\Controllers\User\BusinessApplicationController::class, 'printPermit'])
        ->name('permit.print');

    // Profile
    Route::get('/profile', [UserDashboardController::class, 'profile'])->name('profile');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile/info', [ProfileController::class, 'updateInfo'])->name('profile.info');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile/delete', [ProfileController::class, 'deleteAccount'])->name('profile.delete');


    // Notifications (mark as read via AJAX)
    Route::post('/notifications/{notification}/read', function ($userId, $notification) {
        $notif = \App\Models\Notification::findOrFail($notification);
        $notif->update(['is_read' => true]);
        return response()->json(['success' => true]);
    })->name('notifications.read');
});


// ============================================================
// EMPLOYEE ROUTES (must be logged in as EMPLOYEE)
// ============================================================
Route::prefix('employee/portal')
    ->name('employee.')
    ->middleware('auth.employee')
    ->group(function () {

    // ── SHARED (all employee roles) ─────────────────────────
    Route::get('/dashboard', [App\Http\Controllers\Employee\EmployeeDashboardController::class, 'index'])
        ->name('dashboard');
    //Notification
     Route::get('/notifications', [App\Http\Controllers\Employee\NotificationController::class, 'index'])
        ->name('notifications.index');

    Route::post('/notifications/{notification}/read', [App\Http\Controllers\Employee\NotificationController::class, 'markAsRead'])
        ->name('notifications.read');

    Route::post('/notifications/read-all', [App\Http\Controllers\Employee\NotificationController::class, 'markAllAsRead'])
        ->name('notifications.readAll');

    // ── ADMIN ONLY ───────────────────────────────────────────
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

        // Manage Employees
        Route::get('/employees', [App\Http\Controllers\Employee\AdminController::class, 'listEmployees'])
            ->name('employees.index');
        Route::get('/employees/create', [App\Http\Controllers\Employee\AdminController::class, 'createEmployee'])
            ->name('employees.create');
        Route::post('/employees', [App\Http\Controllers\Employee\AdminController::class, 'storeEmployee'])
            ->name('employees.store');
        Route::get('/employees/{employee}/edit', [App\Http\Controllers\Employee\AdminController::class, 'editEmployee'])
            ->name('employees.edit');
        Route::put('/employees/{employee}', [App\Http\Controllers\Employee\AdminController::class, 'updateEmployee'])
            ->name('employees.update');
        Route::delete('/employees/{employee}', [App\Http\Controllers\Employee\AdminController::class, 'destroyEmployee'])
            ->name('employees.destroy');

        Route::put('/employees/{employee}/reset-password', [App\Http\Controllers\Employee\AdminController::class, 'resetEmployeePassword'])
            ->name('employees.reset-password');
        Route::post('/employees/{id}/restore', [App\Http\Controllers\Employee\AdminController::class, 'restoreEmployee'])
            ->name('employees.restore');


        // Manage Users ← new
        Route::get('/users', [App\Http\Controllers\Employee\AdminController::class, 'listUsers'])->name('users.index');
        Route::get('/users/{user}', [App\Http\Controllers\Employee\AdminController::class, 'showUser'])->name('users.show');
        Route::delete('/users/{user}', [App\Http\Controllers\Employee\AdminController::class, 'destroyUser'])->name('users.destroy');
        Route::post('/users/{id}/restore', [App\Http\Controllers\Employee\AdminController::class, 'restoreUser'])->name('users.restore');


        // All Applications overview
        Route::get('/applications', [App\Http\Controllers\Employee\AdminController::class, 'allApplications'])
            ->name('applications.index');
    });

    // ── MANAGER + ADMIN ──────────────────────────────────────
    Route::middleware('role:admin,manager,staff')->prefix('manage')->name('manager.')->group(function () {
        Route::get('/applications', [App\Http\Controllers\Employee\ManagerController::class, 'index'])
            ->name('applications.index');
        Route::get('/applications/{application}', [App\Http\Controllers\Employee\ManagerController::class, 'show'])
            ->name('applications.show');
        Route::post('/applications/{application}/approve', [App\Http\Controllers\Employee\ManagerController::class, 'approve'])
            ->name('applications.approve');
        Route::post('/applications/{application}/reject', [App\Http\Controllers\Employee\ManagerController::class, 'reject'])
            ->name('applications.reject');
        Route::post('/applications/{application}/issue-permit', [App\Http\Controllers\Employee\ManagerController::class, 'issuePermit'])
            ->name('applications.issue');

        Route::get('/applications', [ManagerController::class, 'index'])->name('applications.index');
        Route::get('/applications/{id}', [ManagerController::class, 'show'])->name('applications.show');

    //review
        Route::post('/applications/{application}/review', [App\Http\Controllers\Employee\EmployeeDashboardController::class, 'markUnderReview'])
            ->name('applications.review');

            
        // Verify payments
        Route::post('/payments/{payment}/verify', [App\Http\Controllers\Employee\ManagerController::class, 'verifyPayment'])
            ->name('payments.verify');
    });

    // ── STAFF ────────────────────────────────────────────────
    Route::middleware('role:staff,admin,manager')->prefix('staff')->name('staff.')->group(function () {
        Route::get('/applications', [App\Http\Controllers\Employee\EmployeeDashboardController::class, 'applications'])
            ->name('applications.index');
        Route::get('/applications/{application}', [App\Http\Controllers\Employee\EmployeeDashboardController::class, 'showApplication'])
            ->name('applications.show');
        Route::post('/applications/{application}/review', [App\Http\Controllers\Employee\EmployeeDashboardController::class, 'markUnderReview'])
            ->name('applications.review');
        
        //new
        // Route::post('/applications/{application}/accept', [App\Http\Controllers\Employee\EmployeeDashboardController::class, 'acceptApplication'])
        // ->name('applications.accept');
        // Route::post('/applications/{application}/reject', [App\Http\Controllers\Employee\EmployeeDashboardController::class, 'rejectApplication'])
        // ->name('applications.reject');

    });

    // Notifications
    Route::post('/notifications/{notification}/read', function ($userId, $notification) {

    $notif = \App\Models\Notification::findOrFail($notification);

    // Make sure this notification belongs to the logged-in user
    abort_if($notif->notifiable_id != $userId, 403);
    abort_if($notif->notifiable_type !== \App\Models\User::class, 403);

    $notif->update(['is_read' => true]);

    return response()->json(['success' => true]);

})->name('notifications.read');
});