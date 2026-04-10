<?php

namespace App\Http\Controllers;

use App\Models\BusinessApplication;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;

class AuditController extends Controller
{
    //
    
    /**
     * Show all audit logs across all applications
     */
    public function index(Request $request)
{
    $query = Audit::with(['auditable', 'user'])->latest();

    // Filter by event
    if ($request->filled('event')) {
        $query->where('event', $request->event);
    }

    // Filter by date
    if ($request->filled('date')) {
        $query->whereDate('created_at', $request->date);
    }

    $audits = $query->paginate(20)->withQueryString();

    return view('employee.audits.index', compact('audits'));
}

    /**
     * Show audit logs for a specific application
     */
    public function show($applicationId)
    {
        $application = BusinessApplication::findOrFail($applicationId);

        $audits = $application->audits()
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('employee.audits.show', compact('application', 'audits'));
    }

    
}
