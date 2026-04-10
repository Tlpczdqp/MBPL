@extends('layouts.app')
@section('title', 'Payment Failed')

@section('content')
<div class="max-w-md mx-auto text-center py-10">

    <div class="inline-flex items-center justify-center w-24 h-24
                bg-red-100 rounded-full mb-6">
        <i class="bi bi-x-circle-fill text-red-500 text-5xl"></i>
    </div>

    <h1 class="text-3xl font-bold text-slate-900 mb-2">Payment Failed</h1>
    <p class="text-slate-500 mb-8">
        Your payment was not completed. You can try again below.
    </p>

    {{-- Payment details if available --}}
    @if($payment)
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 text-left mb-6 space-y-3">
        <h2 class="font-semibold text-slate-700 text-center mb-4">Payment Details</h2>

        @foreach([
            ['Application No.', $application->application_number],
            ['Business Name',   $application->business_name],
            ['Reference No.',   $payment->reference_number ?? '—'],
            ['Status',          strtoupper($payment->status)],
        ] as [$label, $value])
            <div class="flex justify-between text-sm py-1.5 border-b border-slate-100">
                <span class="text-slate-500">{{ $label }}</span>
                <span class="font-medium text-slate-800">{{ $value }}</span>
            </div>
        @endforeach

        <div class="flex justify-between items-center pt-2">
            <span class="font-bold text-slate-900">Amount</span>
            <span class="font-bold text-2xl text-red-500">
                ₱{{ number_format($payment->amount, 2) }}
            </span>
        </div>

        <div class="flex justify-center mt-2">
            <span class="inline-flex items-center gap-1.5 bg-red-100 text-red-700
                         text-xs font-semibold px-3 py-1 rounded-full">
                <i class="bi bi-x-circle-fill"></i>
                Failed
            </span>
        </div>
    </div>
    @endif

    {{-- Possible reasons --}}
    <div class="bg-red-50 border border-red-100 rounded-xl p-4 text-left mb-6">
        <p class="text-sm font-semibold text-red-700 mb-2">
            <i class="bi bi-exclamation-triangle-fill me-1"></i>
            Possible reasons:
        </p>
        <ul class="text-sm text-red-600 list-disc pl-5 space-y-1">
            <li>Card was declined by your bank</li>
            <li>Insufficient balance</li>
            <li>You cancelled the checkout</li>
            <li>Session expired before payment was completed</li>
        </ul>
    </div>

    {{-- Actions --}}
    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        {{-- Try Again --}}
        <a href="{{ route('user.payment.show', [
                'userId'      => $userId,
                'application' => $application->id,
            ]) }}"
           class="inline-flex items-center justify-center gap-2
                  bg-blue-600 hover:bg-blue-700 text-white
                  font-semibold py-3 px-6 rounded-xl transition shadow-md">
            <i class="bi bi-arrow-repeat"></i>
            Try Again
        </a>

        {{-- Back to Application --}}
        <a href="{{ route('user.business.show', [
                'userId'      => $userId,
                'application' => $application->id,
            ]) }}"
           class="inline-flex items-center justify-center gap-2
                  border border-slate-200 text-slate-600 hover:bg-slate-50
                  font-medium py-3 px-6 rounded-xl transition">
            <i class="bi bi-arrow-left"></i>
            Back to Application
        </a>
    </div>
</div>
@endsection