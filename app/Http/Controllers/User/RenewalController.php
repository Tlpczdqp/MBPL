<?php

namespace App\Http\Controllers;

use App\Models\BusinessApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RenewalController extends Controller
{
    public function create(BusinessApplication $application)
    {
        // Only the owner can renew
        if ($application->user_id !== Auth::id()) {
            abort(403);
        }

        // Only permit_issued can be renewed
        if ($application->status !== 'permit_issued') {
            return redirect()
                ->route('applications.index')
                ->with('error', 'Only issued permits can be renewed.');
        }

        return view('user.applications.renew', compact('application'));
    }

    public function store(Request $request, BusinessApplication $application)
    {
        if ($application->user_id !== Auth::id()) {
            abort(403);
        }

        if ($application->status !== 'permit_issued') {
            return redirect()
                ->route('applications.index')
                ->with('error', 'Only issued permits can be renewed.');
        }

        $validated = $request->validate([
            // Step 1 — Business Info
            'business_name'       => ['required', 'string', 'max:255'],
            'business_type'       => ['required', 'string', 'max:255'],
            'billing_frequency'   => ['required', 'in:Annually,Bi-Annually,Quarterly'],
            'trade_name'          => ['nullable', 'string', 'max:255'],
            'dti_registration_no' => ['required', 'string', 'max:100'],
            'tin'                 => ['required', 'string', 'max:50'],
            'renewal_year'        => ['required', 'integer', 'min:' . now()->year],
            'renewal_date'        => ['required', 'date', 'after_or_equal:today'],

            // Step 2 — Address & Owner
            'house_bldg_no'       => ['nullable', 'string', 'max:50'],
            'building_name'       => ['nullable', 'string', 'max:255'],
            'lot_no'              => ['nullable', 'string', 'max:50'],
            'block_no'            => ['nullable', 'string', 'max:50'],
            'street'              => ['nullable', 'string', 'max:255'],
            'barangay'            => ['required', 'string', 'max:100'],
            'subdivision'         => ['nullable', 'string', 'max:255'],
            'city'                => ['required', 'string', 'max:100'],
            'province'            => ['required', 'string', 'max:100'],
            'zip_code'            => ['required', 'string', 'max:10'],
            'owner_name'          => ['required', 'string', 'max:255'],
            'owner_position'      => ['nullable', 'string', 'max:100'],

            // Step 3 — Contact & Activity
            'telephone_number'    => ['nullable', 'string', 'max:20'],
            'owner_contact'       => ['required', 'string', 'max:20'],
            'owner_email'         => ['required', 'email', 'max:255'],
            'business_activity'   => ['required', 'string', 'max:100'],

            // Step 4 — Documents
            'dti_certificate'     => ['required', 'file', 'mimes:jpg,jpeg,png,pdf,gif', 'max:2048'],
            'valid_id'            => ['required', 'file', 'mimes:jpg,jpeg,png,pdf,gif', 'max:2048'],
            'photo_of_business'   => ['required', 'file', 'mimes:jpg,jpeg,png,pdf,gif', 'max:2048'],
            'location_sketch'     => ['required', 'file', 'mimes:jpg,jpeg,png,pdf,gif', 'max:2048'],
        ]);

        // Generate new application number
        $count      = BusinessApplication::whereYear('created_at', now()->year)->count() + 1;
        $appNumber  = 'MBPL-' . now()->year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);

        $renewal = BusinessApplication::create([
            'user_id'              => Auth::id(),
            'application_number'   => $appNumber,
            'application_type'     => 'renewal',
            'renewal_of'           => $application->id,
            'status'               => 'pending',
            'business_name'        => $validated['business_name'],
            'business_type'        => $validated['business_type'],
            'business_description' => $validated['business_description'] ?? null,
            'business_address'     => $validated['business_address'],
            'barangay'             => $validated['barangay'],
            'city'                 => $validated['city'],
            'owner_name'           => $validated['owner_name'],
            'owner_contact'        => $validated['owner_contact'],
            'owner_email'          => $validated['owner_email'],
            'capital_investment'   => $validated['capital_investment'],
            'num_employees'        => $validated['num_employees'],
            'renewal_year'         => $validated['renewal_year'],
            'remarks'              => $validated['remarks'] ?? null,
        ]);

        return redirect()
            ->route('applications.show', $renewal)
            ->with('success', "Renewal application {$appNumber} submitted successfully.");
    }
}
