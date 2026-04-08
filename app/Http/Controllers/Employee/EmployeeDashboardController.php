<?php
namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\BusinessApplication;
use App\Models\Employee;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class EmployeeDashboardController extends Controller
{
    /**
     * Get the authenticated employee or abort.
     *
     * @return Employee
     */
    private function authenticatedEmployee(): Employee
    {
        $employee = Auth::guard('employee')->user();

        abort_if(
            !$employee instanceof Employee,
            403,
            'Unauthorized. Employee session not found.'
        );

        /** @var Employee $employee */
        return $employee;
    }

    public function index()
    {
        $employee = $this->authenticatedEmployee(); // ✅ IDE knows this is Employee

        $stats = [
            'total'        => BusinessApplication::count(),
            'pending'      => BusinessApplication::where('status', 'pending')->count(),
            'under_review' => BusinessApplication::where('status', 'under_review')->count(),
            'approved'     => BusinessApplication::where('status', 'approved')->count(),
            'paid'         => BusinessApplication::where('status', 'paid')->count(),
            'rejected'     => BusinessApplication::where('status', 'rejected')->count(),
            'issued'       => BusinessApplication::where('status', 'permit_issued')->count(),
        ];

        if ($employee->isStaff()) {                    // ✅ No warning
            $recentApplications = BusinessApplication::with('user')
                ->where('processed_by', $employee->id)
                ->latest()
                ->take(5)
                ->get();

        } elseif ($employee->isManager()) {             // ✅ No warning
            $recentApplications = BusinessApplication::with('user')
                ->whereIn('status', ['pending', 'under_review', 'paid'])
                ->latest()
                ->take(5)
                ->get();

        } else {
            $recentApplications = BusinessApplication::with('user')
                ->latest()
                ->take(5)
                ->get();
        }

        $myProcessedCount = 0;
        if ($employee->isStaff()) {                     // ✅ No warning
            $myProcessedCount = BusinessApplication::where('processed_by', $employee->id)
                ->count();
        }

        $totalEmployees = 0;
        if ($employee->isAdmin()) {                     // ✅ No warning
            $totalEmployees = Employee::where('is_active', true)->count();
        }

        return view('employee.dashboard', compact(
            'employee',
            'stats',
            'recentApplications',
            'myProcessedCount',
            'totalEmployees'
        ));
    }

    public function applications()
    {
        $employee = $this->authenticatedEmployee();     // ✅ Clean

        $applications = BusinessApplication::with('user')
            ->whereIn('status', ['pending', 'under_review'])
            ->latest()
            ->paginate(15);

        return view('employee.staff.applications.index', compact('applications'));
    }

    public function showApplication(BusinessApplication $application)
    {
        $employee = $this->authenticatedEmployee();     // ✅ Clean

        $application->load(['user', 'documents', 'payment', 'processedBy']);

        return view('employee.staff.applications.show', compact('application'));
    }

    public function markUnderReview(BusinessApplication $application)
    {
        $employee = $this->authenticatedEmployee();     // ✅ Clean

        abort_if(
            $application->status !== 'pending',
            403,
            'Only pending applications can be marked as under review.'
        );

        $application->update([
            'status'       => 'under_review',
            'processed_by' => $employee->id,
        ]);

        Notification::create([
            'notifiable_type' => \App\Models\User::class,
            'notifiable_id'   => $application->user_id,
            'title'           => 'Application Under Review',
            'message'         => 'Your application '
                                 . $application->application_number
                                 . ' is now being reviewed by our team.',
            'link'            => null,
            'is_read'         => false,
        ]);

        return back()->with('success', 'Application marked as under review.');
    }
}