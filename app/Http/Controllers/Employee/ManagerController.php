<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\BusinessApplication;
use App\Models\BusinessDocument;
use App\Models\Notification;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ManagerController extends Controller
{
    // Show all applications (manager sees all, not just their own)
    // public function index()
    // {
    //     $applications = BusinessApplication::with(['user', 'processedBy'])
    //         ->latest()
    //         ->paginate(15);

    //     // Count by status for the dashboard cards
    //     $counts = [
    //         'pending'      => BusinessApplication::where('status', 'pending')->count(),
    //         'under_review' => BusinessApplication::where('status', 'under_review')->count(),
    //         'approved'     => BusinessApplication::where('status', 'approved')->count(),
    //         'rejected'     => BusinessApplication::where('status', 'rejected')->count(),
    //         'paid'         => BusinessApplication::where('status', 'paid')->count(),
    //         'issued'       => BusinessApplication::where('status', 'permit_issued')->count(),
    //     ];

    //     return view('employee.manager.applications.index', compact('applications', 'counts'));
    // }
    public function index(Request $request)
    {
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

        return view('employee.manager.applications.index', compact('applications', 'counts'));
    }

    public function show(BusinessApplication $application)
    {
        // Eager load related data to avoid N+1 queries
        $application->load(['user', 'documents', 'payment', 'processedBy']);
        return view('employee.manager.applications.show', compact('application'));
    }

    public function approve(Request $request, BusinessApplication $application)
    {
        $request->validate([
            'permit_fee' => 'required|numeric|min:1',
        ]);

        $application->update([
            'status'       => 'approved',
            'permit_fee'   => $request->permit_fee,
            'processed_by' => Auth::guard('employee')->id(),
        ]);

        // Notify the user their application was approved
        Notification::create([
            'notifiable_type' => 'App\Models\User',
            'notifiable_id'   => $application->user_id,
            'title'           => 'Application Approved! 🎉',
            'message'         => 'Your application ' . $application->application_number .
                ' has been approved. Permit fee: ₱' . number_format($application->permit_fee, 2) .
                '. Please proceed to payment.',
            'link'            => route('user.payment.show', [
                'userId'      => $application->user_id,
                'application' => $application->id,
            ]),
        ]);

        return back()->with('success', 'Application approved and user notified.');
    }

    public function reject(Request $request, BusinessApplication $application)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $application->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'processed_by'     => Auth::guard('employee')->id(),
        ]);

        // Notify the user
        Notification::create([
            'notifiable_type' => 'App\Models\User',
            'notifiable_id'   => $application->user_id,
            'title'           => 'Application Rejected',
            'message'         => 'Your application ' . $application->application_number .
                ' was rejected. Reason: ' . $request->rejection_reason,
            'link'            => route('user.business.show', [
                'userId'      => $application->user_id,
                'application' => $application->id,
            ]),
        ]);

        return back()->with('success', 'Application rejected and user notified.');
    }

    public function issuePermit(Request $request, BusinessApplication $application)
    {
        // Can only issue if payment is verified
        abort_if($application->status !== 'paid', 403, 'Payment must be verified first.');

        $application->load('payment');

        $issuedAt = $application->payment?->paid_at ?? now();

        $application->update([
            'status'             => 'permit_issued',
            'permit_issued_at'   => $issuedAt,
            'permit_valid_until' => $application->getPermitValidityDate($issuedAt)->toDateString(),
            'processed_by'       => Auth::guard('employee')->id(),
        ]);

        // Notify user — permit is ready!
        Notification::create([
            'notifiable_type' => 'App\Models\User',
            'notifiable_id'   => $application->user_id,
            'title'           => 'Business Permit Issued! 🏆',
            'message'         => 'Your business permit for ' . $application->business_name .
                ' has been issued. You can now print your permit.',
            'link'            => route('user.permit.print', [
                'userId'      => $application->user_id,
                'application' => $application->id,
            ]),
        ]);

        return back()->with('success', 'Permit issued successfully!');
    }

    public function verifyPayment(Request $request, Payment $payment)
    {
        $payment->update([
            'status' => 'verified',
        ]);

        // Update application status
        $payment->businessApplication->update(['status' => 'paid']);

        Notification::create([
            'notifiable_type' => 'App\Models\User',
            'notifiable_id'   => $payment->user_id,
            'title'           => 'Payment Verified ✅',
            'message'         => 'Your payment has been verified. Your permit will be issued shortly.',
        ]);

        return back()->with('success', 'Payment verified.');
    }
    public function viewDocument($userId, BusinessApplication $application, BusinessDocument $document)
{
    $isUserOwner =
        Auth::guard('web')->check() &&
        (int) Auth::guard('web')->id() === (int) $userId &&
        (int) $application->user_id === (int) Auth::guard('web')->id();

    $isEmployee = Auth::guard('employee')->check();

    abort_unless($isUserOwner || $isEmployee, 403);

    abort_if((int) $document->business_application_id !== (int) $application->id, 404);

    if (!Storage::exists($document->file_path)) {
        abort(404, 'Document file not found.');
    }

    return response()->file(
        Storage::path($document->file_path),
        [
            'Content-Type' => $document->mime_type ?? 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="' . ($document->file_name ?? 'document') . '"',
        ]
    );
}
}
