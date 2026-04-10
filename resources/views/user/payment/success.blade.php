{{-- resources/views/user/payment/success.blade.php --}}
@extends('layouts.app')
@section('title', 'Payment Successful')

@section('content')
<div class="max-w-md mx-auto text-center py-10">

    <div class="inline-flex items-center justify-center w-24 h-24
                bg-green-100 rounded-full mb-6">
        <i class="bi bi-check-circle-fill text-green-500 text-5xl"></i>
    </div>

    <h1 class="text-3xl font-bold text-slate-900 mb-2">Payment Successful!</h1>
    <p class="text-slate-500 mb-8">
        Your payment has been received and is pending admin verification.
    </p>

    @if($payment)
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 text-left mb-6 space-y-3">
        <h2 class="font-semibold text-slate-700 text-center mb-4">Receipt Summary</h2>

        @foreach([
            ['Application No.',  $application->application_number],
            ['Business Name',    $application->business_name],
            ['Payment Method',   strtoupper($payment->payment_method ?? '—')],
            ['Reference No.',    $payment->reference_number ?? $payment->paymongo_payment_id ?? '—'],
            ['Date Paid',        $payment->paid_at?->format('M d, Y h:i A') ?? '—'],
        ] as [$label, $value])
            <div class="flex justify-between text-sm py-1.5 border-b border-slate-100">
                <span class="text-slate-500">{{ $label }}</span>
                <span class="font-medium text-slate-800 font-mono">{{ $value }}</span>
            </div>
        @endforeach

        <div class="flex justify-between items-center pt-2">
            <span class="font-bold text-slate-900">Amount Paid</span>
            <span class="font-bold text-2xl text-green-600">
                ₱{{ number_format($payment->amount, 2) }}
            </span>
        </div>

        <div class="flex justify-center mt-2">
            <span class="inline-flex items-center gap-1.5 bg-green-100 text-green-700
                         text-xs font-semibold px-3 py-1 rounded-full">
                <i class="bi bi-check-circle-fill"></i>
                Paid
            </span>
        </div>
    </div>
    @endif

    <a href="{{ route('user.business.show', ['userId' => $userId, 'application' => $application->id]) }}"
       class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700
              text-white font-semibold py-3 px-6 rounded-xl transition shadow-md">
        <i class="bi bi-arrow-left"></i>
        Back to Application
    </a>
</div>
@endsection