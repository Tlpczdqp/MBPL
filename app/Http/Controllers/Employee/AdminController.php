<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\BusinessApplication;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function listEmployees(Request $request)
    {
        // $employees = Employee::latest()->paginate(15);
        // return view('employee.admin.employees.index', compact('employees'));
        $search     = $request->input('search');
        $role       = $request->input('role');
        $department = $request->input('department');

        $employees = Employee::query()
            ->whereIn('role', ['staff', 'manager', 'admin'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('employee_id', 'like', "%{$search}%");
                });
            })
            ->when($role, fn($q) => $q->where('role', $role))
            ->when($department, fn($q) => $q->where('department', $department))
            ->withTrashed()  // show soft-deleted (inactive) too
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $departments = Employee::whereIn('role', ['staff', 'manager', 'admin'])
            ->whereNotNull('department')
            ->distinct()
            ->pluck('department');

        $counts = [
            'total'   => Employee::whereIn('role', ['staff', 'manager', 'admin'])->count(),
            'staff'   => Employee::where('role', 'staff')->count(),
            'manager' => Employee::where('role', 'manager')->count(),
            'admin'   => Employee::where('role', 'admin')->count(),
        ];

        return view('employee.admin.employees.index', compact('employees', 'departments', 'counts'));
    }

    public function createEmployee()
    {
        return view('employee.admin.employees.create');
    }

    public function storeEmployee(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:employees,email',
            'password'    => 'required|string|min:8|confirmed',
            'role'        => 'required|in:admin,manager,staff',
            'employee_id' => 'nullable|string|unique:',
            'department'  => 'nullable|string|max:255',
        ]);

        Employee::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => $request->role,
            'employee_id' => $request->employee_id,
            'department'  => $request->department,
            'is_active'   => true,
        ]);

        return redirect()->route('employee.admin.employees.index')
            ->with('success', 'Employee account created successfully.');
    }

    public function editEmployee(Employee $employee)
    {
        return view('employee.admin.employees.edit', compact('employee'));
    }

    public function updateEmployee(Request $request, Employee $employee)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:employees,email,' . $employee->id,
            'role'       => 'required|in:admin,manager,staff',
            'department' => 'nullable|string|max:255',
            'is_active'  => 'boolean',
            // Password is optional on update
            'password'   => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name'       => $request->name,
            'email'      => $request->email,
            'role'       => $request->role,
            'department' => $request->department,
            'is_active'  => $request->boolean('is_active'),
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $employee->update($data);

        return redirect()->route('employee.admin.employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    //restore employee
    public function restoreEmployee(int $id)
    {
        $employee = Employee::withTrashed()->findOrFail($id);
        $employee->restore();

        return redirect()
            ->route('employee.admin.employees.index')
            ->with('success', "Employee {$employee->name} has been reactivated.");
    }

    //Soft Delete Employee
    public function destroyEmployee(Employee $employee)
    {
        // Prevent admin from deleting themselves
        if ($employee->id === auth('employee')->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $employee->delete();

        return redirect()->route('employee.admin.employees.index')
            ->with('success', 'Employee deleted.');
    }

    public function allApplications(Request $request)
    {
        // $applications = \App\Models\BusinessApplication::with(['user', 'processedBy'])
        //     ->latest()
        //     ->paginate(20);

        //trial(like manager)
        $query = BusinessApplication::with(['user', 'payment']);

        // Status filter
        $status = $request->input('status', 'all');
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('application_number', 'like', "%{$search}%")
                    ->orWhere('business_name', 'like', "%{$search}%")
                    ->orWhereHas(
                        'user',
                        fn($u) =>
                        $u->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                    );
            });
        }

        // Sort
        match ($request->input('sort', 'newest')) {
            'oldest'        => $query->orderBy('created_at', 'asc'),
            'business_name' => $query->orderBy('business_name', 'asc'),
            default         => $query->orderBy('created_at', 'desc'),
        };

        $applications = $query->paginate(15);

        // Counts for filter cards
        $counts = [
            'all'           => BusinessApplication::count(),
            'pending'       => BusinessApplication::where('status', 'pending')->count(),
            'under_review'  => BusinessApplication::where('status', 'under_review')->count(),
            'approved'      => BusinessApplication::where('status', 'approved')->count(),
            'paid'          => BusinessApplication::where('status', 'paid')->count(),
            'rejected'      => BusinessApplication::where('status', 'rejected')->count(),
            'permit_issued' => BusinessApplication::where('status', 'permit_issued')->count(),
        ];


        return view('employee.admin.applications.index', compact('applications', 'counts'));
    }

    //Manage User
    public function listUsers(Request $request)
{
    $search = $request->input('search');
    $status = $request->input('status');

    $users = \App\Models\User::query()
        ->when($search, function ($q) use ($search) {
            $q->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        })
        ->when($status === 'active',       fn($q) => $q->whereNull('deleted_at'))
        ->when($status === 'inactive',     fn($q) => $q->whereNotNull('deleted_at'))
        ->when($status === 'unverified',   fn($q) => $q->where('email_verified', false))
        ->withTrashed()
        ->latest()
        ->paginate(15)
        ->withQueryString();

    $counts = [
        'total'      => \App\Models\User::withTrashed()->count(),
        'active'     => \App\Models\User::count(),
        'inactive'   => \App\Models\User::onlyTrashed()->count(),
        'unverified' => \App\Models\User::where('email_verified', false)->count(),
    ];

    return view('employee.admin.users.index', compact('users', 'counts'));
}

/**
 * Show a single user with their applications.
 */
public function showUser(\App\Models\User $user)
{
    // Load with trashed so we can still view deactivated users
    $user = \App\Models\User::withTrashed()->findOrFail($user->id);
    $user->load('businessApplications');

    return view('employee.admin.users.show', compact('user'));
}

/**
 * Soft delete (deactivate) a user.
 */
public function destroyUser(\App\Models\User $user)
{
    $name = $user->name;
    $user->delete();

    return redirect()
        ->route('employee.admin.users.index')
        ->with('success', "User {$name} has been deactivated.");
}

/**
 * Restore a soft-deleted user.
 */
public function restoreUser(int $id)
{
    $user = \App\Models\User::withTrashed()->findOrFail($id);
    $user->restore();

    return redirect()
        ->route('employee.admin.users.index')
        ->with('success', "User {$user->name} has been reactivated.");
}
}
