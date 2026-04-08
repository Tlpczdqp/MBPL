<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BusinessApplication;
use App\Models\BusinessDocument;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BusinessApplicationController extends Controller
{
    // List all applications belonging to the logged-in user
    public function index($userId)
    {
        $applications = BusinessApplication::where('user_id', $userId)
            ->latest()
            ->paginate(10);

        return view('user.applications.index', compact('applications'));
    }

    public function create($userId)
    {
        return view('user.applications.create');
    }

    public function store(Request $request, $userId)
    {
        // Validate the form data
        // dd($request);
        $validated = $request->validate([
            'billing_freq'      => 'required|in:Annually,Bi-Annually,Quarterly',
            'business_info'     => 'required|in:Sole Proprietorship,One Person Corporation,Partnerships,Corporation,Cooperation',
            'business_name'     => 'required|string|max:255',
            'trade_name'        => 'nullable|string|max:255',
            'reg_num'           => 'required|string|max:100',
            'business_tin'      => 'required|string|max:50',
            'telephone_num'     => 'nullable|string|max:20',
            'phone_number'      => 'nullable|string|max:20',
            'business_email'    => 'required|email|max:255',
            'house_num'         => 'nullable|string',
            'building_name'     => 'nullable|string',
            'lot_num'           => 'nullable|string',
            'block_num'         => 'nullable|string',
            'street'            => 'nullable|string',
            'barangay'          => 'required|string',
            'subdivision'       => 'nullable|string',
            'city_muni'         => 'required|string',
            'province'          => 'required|string',
            'zip_code'          => 'required|string',
            'sp_owner_lname'    => 'nullable|string',
            'sp_owner_fname'    => 'nullable|string',
            'sp_owner_mname'    => 'nullable|string',
            'corp_owner_lname'  => 'nullable|string',
            'corp_owner_fname'  => 'nullable|string',
            'corp_owner_mname'  => 'nullable|string',
            'corp_location'     => 'nullable|in:Local,Foreign',
            'business_act'      => 'required|in:Main Office,Branch Office,Admin Office Only,Warehouse,Others',
            'business_act_other' => 'nullable|string',
            // 4 required documents
            'dti_sec_certificate' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'valid_id'            => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'business_photo'      => 'required|file|mimes:jpg,jpeg,png,gif|max:2048',
            'business_sketch'     => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Create the application record
        $application = BusinessApplication::create([
            'user_id'              => $userId,
            'transact_type'        => 'New',
            'billing_freq'         => $validated['billing_freq'],
            'business_info'        => $validated['business_info'],
            'business_name'        => $validated['business_name'],
            'trade_name'           => $validated['trade_name'],
            'reg_num'              => $validated['reg_num'],
            'business_tin'         => $validated['business_tin'],
            'telephone_num'        => $validated['telephone_num'],
            'phone_number'         => $validated['phone_number'],
            'business_email'       => $validated['business_email'],
            'house_num'            => $validated['house_num'],
            'building_name'        => $validated['building_name'],
            'lot_num'              => $validated['lot_num'],
            'block_num'            => $validated['block_num'],
            'street'               => $validated['street'],
            'barangay'             => $validated['barangay'],
            'subdivision'          => $validated['subdivision'],
            'city_muni'            => $validated['city_muni'],
            'province'             => $validated['province'],
            'zip_code'             => $validated['zip_code'],
            'sp_owner_lname'       => $validated['sp_owner_lname'],
            'sp_owner_fname'       => $validated['sp_owner_fname'],
            'sp_owner_mname'       => $validated['sp_owner_mname'],
            'corp_owner_lname'     => $validated['corp_owner_lname'],
            'corp_owner_fname'     => $validated['corp_owner_fname'],
            'corp_owner_mname'     => $validated['corp_owner_mname'],
            'corp_location'        => $validated['corp_location'] ?? null,
            'business_act'         => $validated['business_act'],
            // 'business_act_other'   => $validated['business_act_other'],
            'status'               => 'pending',
            'application_number'   => BusinessApplication::generateApplicationNumber(),
        ]);

        // Store the 4 documents
        $documentFields = [
            'dti_sec_certificate' => 'DTI/SEC Certificate',
            'valid_id'            => 'Valid ID',
            'business_photo'      => 'Business Photo',
            'business_sketch'     => 'Business Sketch',
        ];

        foreach ($documentFields as $field => $label) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                // Store in storage/app/private/documents/{application_id}/
                $path = $file->store("documents/{$application->id}", 'local');

                BusinessDocument::create([
                    'business_application_id' => $application->id,
                    'document_type'           => $field,
                    'file_path'               => $path,
                    'file_name'               => $file->getClientOriginalName(),
                    'file_size'               => $file->getSize(),
                    'mime_type'               => $file->getMimeType(),
                ]);
            }
        }

        // Send notification to user
        Notification::create([
            'notifiable_type' => \App\Models\User::class,  // stores "App\Models\User"
            'notifiable_id'   => $userId,
            'title'           => 'Application Submitted',
            'message'         => 'Your application ' . $application->application_number
                . ' has been submitted.',
            'link'            => route('user.business.show', [
                'userId'      => $userId,
                'application' => $application->id,
            ]),
            'is_read'         => false,
        ]);
        return redirect()->route('user.business.index', ['userId' => $userId])
            ->with('success', 'Application submitted successfully! Application #: ' . $application->application_number);
    }

    public function show($userId, BusinessApplication $application)
    {
        // Make sure the application belongs to this user (security check!)
        abort_if($application->user_id != $userId, 403);

        $application->load(['documents', 'payment']);
        return view('user.applications.show', compact('application'));
    }

    // Show the renewal form pre-filled with old data
    public function renew($userId, BusinessApplication $application)
    {
        abort_if($application->user_id != $userId, 403);
        abort_if($application->status !== 'permit_issued', 403, 'Only issued permits can be renewed.');

        return view('user.applications.renew', compact('application'));
    }

    public function storeRenewal(Request $request, $userId, BusinessApplication $application)
    {
        abort_if($application->user_id != $userId, 403);

        // Similar to store() but transact_type = 'Renewal'
        // (abbreviated for space — same validation as store)
        // ...
        // Create new application with transact_type = 'Renewal'
        $newApplication = $application->replicate(); // copy the old application
        $newApplication->transact_type     = 'Renewal';
        $newApplication->status            = 'pending';
        $newApplication->application_number = BusinessApplication::generateApplicationNumber();
        $newApplication->permit_issued_at  = null;
        $newApplication->permit_valid_until = null;
        $newApplication->processed_by      = null;
        $newApplication->rejection_reason  = null;
        $newApplication->save();

        return redirect()->route('user.business.show', ['userId' => $userId, 'application' => $newApplication->id])
            ->with('success', 'Renewal application submitted!');
    }

    // Print the permit (only if issued)
    public function printPermit($userId, BusinessApplication $application)
    {
        abort_if($application->user_id != $userId, 403);
        abort_if(!$application->canPrint(), 403, 'Permit has not been issued yet.');

        return view('permits.print', compact('application'));
    }

    public function viewDocument($userId, BusinessApplication $application, BusinessDocument $document)
{
    // Security: ensure document belongs to this application and user
    abort_if($application->user_id != $userId, 403);
    abort_if($document->business_application_id != $application->id, 403);

    // Check file exists
    abort_if(!Storage::disk('local')->exists($document->file_path), 404, 'File not found.');

    // Return file inline (viewable in browser) or download
    return response()->file(
        Storage::disk('local')->path($document->file_path),
        [
            'Content-Type' => $document->mime_type,
            'Content-Disposition' => 'inline; filename="' . $document->file_name . '"',
        ]
    );
}

/**
 * Delete an application (only if pending or rejected)
 */
public function destroy($userId, BusinessApplication $application)
{
    abort_if($application->user_id != $userId, 403);

    // Only allow deletion if pending or rejected
    abort_if(
        !in_array($application->status, ['pending', 'rejected']),
        403,
        'Only pending or rejected applications can be deleted.'
    );

    // Delete all uploaded document files from storage
    foreach ($application->documents as $doc) {
        if (Storage::disk('local')->exists($doc->file_path)) {
            Storage::disk('local')->delete($doc->file_path);
        }
        $doc->delete();
    }

    // Delete the directory if empty
    $directory = "documents/{$application->id}";
    if (Storage::disk('local')->exists($directory)) {
        Storage::disk('local')->deleteDirectory($directory);
    }

    // Delete payment if exists
    if ($application->payment) {
        $application->payment->delete();
    }

    // Delete notifications related to this application
    Notification::where('notifiable_type', \App\Models\User::class)
        ->where('notifiable_id', $userId)
        ->where('message', 'like', '%' . $application->application_number . '%')
        ->delete();

    // Delete the application
    $application->delete();

    return redirect()->route('user.business.index', ['userId' => $userId])
        ->with('success', 'Application deleted successfully.');
    }
}
