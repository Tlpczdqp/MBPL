<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    private function authenticatedEmployee(): Employee
    {
        $employee = Auth::guard('employee')->user();
        abort_if(!$employee instanceof Employee, 403, 'Unauthorized.');

        /** @var Employee $employee */
        return $employee;
    }

    public function index()
    {
        $employee = $this->authenticatedEmployee();

        $notifications = Notification::where('notifiable_type', Employee::class)
            ->where('notifiable_id', $employee->id)
            ->latest()
            ->paginate(20);

        return view('employee.notifications.index', compact('employee', 'notifications'));
    }

    public function markAsRead(Notification $notification)
    {
        $employee = $this->authenticatedEmployee();

        abort_if($notification->notifiable_id != $employee->id, 403);
        abort_if($notification->notifiable_type !== Employee::class, 403);

        $notification->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        $employee = $this->authenticatedEmployee();

        Notification::where('notifiable_type', Employee::class)
            ->where('notifiable_id', $employee->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'All notifications marked as read.');
    }
}