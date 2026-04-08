<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BusinessApplication;
use App\Models\Notification;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function showPayment($userId, BusinessApplication $application)
    {
        abort_if($application->user_id != $userId, 403);
        abort_if($application->status !== 'approved', 403, 'Application must be approved first.');

        return view('user.payment.index', compact('application'));
    }

    public function submitPayment(Request $request, $userId, BusinessApplication $application)
    {
        abort_if($application->user_id != $userId, 403);

        $request->validate([
            'payment_method'    => 'required|in:gcash,paymaya,bank_transfer',
            'reference_number'  => 'required|string|max:100',
            'proof_image'       => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $proofPath = $request->file('proof_image')->store("payments/{$application->id}", 'local');

        Payment::create([
            'business_application_id' => $application->id,
            'user_id'                 => $userId,
            'amount'                  => $application->permit_fee,
            'payment_method'          => $request->payment_method,
            'reference_number'        => $request->reference_number,
            'proof_image'             => $proofPath,
            'status'                  => 'pending', // employee will verify
            'paid_at'                 => now(),
        ]);

        $application->update(['status' => 'paid']);

        // Notify user
        Notification::create([
            'notifiable_type' => 'App\Models\User',
            'notifiable_id'   => $userId,
            'title'           => 'Payment Submitted',
            'message'         => 'Your payment for ' . $application->application_number . ' is being verified.',
        ]);

        return redirect()->route('user.business.show', ['userId' => $userId, 'application' => $application->id])
            ->with('success', 'Payment submitted! Please wait for verification.');
    }
}