<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\BusinessApplication;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function show($userId, BusinessApplication $application)
    {
        if ((int) $userId !== Auth::id()) {
            abort(403);
        }

        if ($application->user_id !== Auth::id()) {
            abort(403);
        }

        return view('user.payment.show', compact('application'));
    }

    public function submit($userId, BusinessApplication $application)
    {
        if ((int) $userId !== Auth::id()) {
            abort(403);
        }

        if ($application->user_id !== Auth::id()) {
            abort(403);
        }

        if ($application->status !== 'approved') {
            return back()->with('error', 'This application is not eligible for payment.');
        }

        if (!$application->permit_fee || $application->permit_fee <= 0) {
            return back()->with('error', 'Invalid permit fee.');
        }

        $secretKey = config('services.paymongo.secret_key');

        if (!$secretKey) {
            return back()->with('error', 'PayMongo secret key is not configured.');
        }

        $amountInCentavos = (int) round($application->permit_fee * 100);

        $referenceNumber = 'APP-' . $application->id . '-' . strtoupper(Str::random(8));

        $successUrl = route('user.payment.success', [
            'userId' => Auth::id(),
            'application' => $application->id,
        ]);

        $failedUrl = route('user.payment.failed', [
            'userId' => Auth::id(),
            'application' => $application->id,
        ]);

        $response = Http::withBasicAuth($secretKey, '')
            ->acceptJson()
            ->post('https://api.paymongo.com/v1/checkout_sessions', [
                'data' => [
                    'attributes' => [
                        'billing' => [
                            'name' => Auth::user()->name,
                            'email' => Auth::user()->email,
                        ],
                        'send_email_receipt' => true,
                        'show_description' => true,
                        'show_line_items' => true,
                        'description' => 'Business Permit Payment - ' . $application->application_number,
                        'line_items' => [
                            [
                                'currency' => 'PHP',
                                'amount' => $amountInCentavos,
                                'description' => 'Permit Fee for ' . $application->business_name,
                                'name' => 'Business Permit Fee',
                                'quantity' => 1,
                            ]
                        ],
                        'payment_method_types' => ['gcash', 'paymaya', 'card'],
                        'success_url' => $successUrl,
                        'cancel_url' => $failedUrl,
                        'metadata' => [
                            'business_application_id' => $application->id,
                            'user_id' => Auth::id(),
                            'reference_number' => $referenceNumber,
                        ],
                    ]
                ]
            ]);

        if (!$response->successful()) {
            return back()->with('error', 'Unable to create PayMongo checkout session.');
        }

        $result = $response->json();

        $checkoutUrl = $result['data']['attributes']['checkout_url'] ?? null;
        $checkoutSessionId = $result['data']['id'] ?? null;

        if (!$checkoutUrl || !$checkoutSessionId) {
            return back()->with('error', 'Invalid PayMongo response.');
        }

        Payment::updateOrCreate(
            ['business_application_id' => $application->id],
            [
                'user_id' => Auth::id(),
                'amount' => $application->permit_fee,
                'status' => 'pending',
                'payment_method' => 'paymongo',
                'reference_number' => $referenceNumber,
                'gateway' => 'paymongo',
                'gateway_reference' => $checkoutSessionId,
            ]
        );

        return redirect()->away($checkoutUrl);
    }

    public function success($userId, BusinessApplication $application)
    {
        if ((int) $userId !== Auth::id()) {
            abort(403);
        }

        if ($application->user_id !== Auth::id()) {
            abort(403);
        }

        DB::transaction(function () use ($application) {
            $payment = Payment::updateOrCreate(
                ['business_application_id' => $application->id],
                [
                    'user_id' => Auth::id(),
                    'amount' => $application->permit_fee,
                    'status' => 'verified',
                    'payment_method' => 'paymongo',
                ]
            );

            $application->update([
                'status' => 'paid',
            ]);
        });

        return redirect()
            ->route('user.business.show', [
                'userId' => Auth::id(),
                'application' => $application->id
            ])
            ->with('success', 'Payment successful. Your business application is now marked as paid.');
    }

    public function fail($userId, BusinessApplication $application)
    {
        if ((int) $userId !== Auth::id()) {
            abort(403);
        }

        if ($application->user_id !== Auth::id()) {
            abort(403);
        }

        Payment::updateOrCreate(
            ['business_application_id' => $application->id],
            [
                'user_id' => Auth::id(),
                'amount' => $application->permit_fee,
                'status' => 'rejected',
                'payment_method' => 'paymongo',
                'reference_number' => 'FAILED-' . strtoupper(Str::random(8)),
            ]
        );

        return back()->with('error', 'Payment failed simulation saved.');
    }

    public function failed($userId, BusinessApplication $application)
    {
        if ((int) $userId !== Auth::id()) {
            abort(403);
        }

        if ($application->user_id !== Auth::id()) {
            abort(403);
        }

        Payment::updateOrCreate(
            ['business_application_id' => $application->id],
            [
                'user_id' => Auth::id(),
                'amount' => $application->permit_fee,
                'status' => 'rejected',
                'payment_method' => 'paymongo',
            ]
        );

        return redirect()
            ->route('user.payment.show', [
                'userId' => Auth::id(),
                'application' => $application->id
            ])
            ->with('error', 'Payment was cancelled or failed.');
    }
}