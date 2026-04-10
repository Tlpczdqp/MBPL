{{-- resources/views/user/payment/show.blade.php --}}
@extends('layouts.app')
@section('title', 'Pay Permit Fee')

@section('content')
<div class="max-w-lg mx-auto">

    {{-- Back --}}
    <a href="{{ route('user.business.show', [
            'userId'      => Auth::id(),
            'application' => $application->id
        ]) }}"
       class="inline-flex items-center gap-2 text-sm font-medium text-slate-600
              hover:text-slate-800 mb-6 transition">
        <i class="bi bi-arrow-left"></i> Back to Application
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-blue-700 to-blue-900 px-8 py-8 text-center">
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="bi bi-shield-check text-white text-3xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-white">Permit Fee Payment</h1>
            <p class="text-blue-200 text-sm mt-1">{{ $application->application_number }}</p>
        </div>

        <div class="p-8 space-y-6">

            {{-- Session Alerts --}}
            @if(session('error'))
                <div class="flex items-start gap-3 bg-red-50 border border-red-200
                            text-red-700 px-4 py-3 rounded-xl text-sm">
                    <i class="bi bi-exclamation-triangle-fill mt-0.5 shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('info'))
                <div class="flex items-start gap-3 bg-blue-50 border border-blue-200
                            text-blue-700 px-4 py-3 rounded-xl text-sm">
                    <i class="bi bi-info-circle-fill mt-0.5 shrink-0"></i>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            {{-- Payment Summary --}}
            <div class="bg-slate-50 rounded-xl p-5 space-y-3">
                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">
                    Payment Summary
                </h3>

                @foreach([
                    ['Business Name',   $application->business_name],
                    ['Business Type',   $application->business_info],
                    ['Billing',         $application->billing_freq],
                    ['Application No.', $application->application_number],
                ] as [$label, $value])
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500">{{ $label }}</span>
                        <span class="font-medium text-slate-800">{{ $value }}</span>
                    </div>
                @endforeach

                <div class="border-t border-slate-200 pt-3 flex items-center justify-between">
                    <span class="font-bold text-slate-900 text-sm">Total Amount Due</span>
                    <span class="font-bold text-2xl text-blue-700">
                        ₱{{ number_format($application->permit_fee, 2) }}
                    </span>
                </div>
            </div>

            {{-- ✅ Payment Status Blocks --}}

            {{-- Already Paid --}}
            @if($application->payment?->isPaid())

                <div class="rounded-xl bg-green-50 border border-green-200 p-5 text-center">
                    <i class="bi bi-check-circle-fill text-green-500 text-3xl mb-2 block"></i>
                    <p class="font-semibold text-green-800">Payment Received</p>
                    <p class="text-sm text-green-600 mt-1">
                        Paid on {{ $application->payment->paid_at?->format('F d, Y h:i A') ?? '—' }}
                    </p>
                    @if($application->payment->reference_number)
                        <p class="text-xs text-green-500 font-mono mt-1">
                            Ref: {{ $application->payment->reference_number }}
                        </p>
                    @endif
                </div>

            {{-- Pending Verification --}}
            @elseif($application->payment?->isPending())

                <div class="rounded-xl bg-yellow-50 border border-yellow-200 p-5 text-center">
                    <i class="bi bi-hourglass-split text-yellow-500 text-3xl mb-2 block"></i>
                    <p class="font-semibold text-yellow-800">Payment Under Verification</p>
                    <p class="text-sm text-yellow-600 mt-1">
                        Your payment is being reviewed by our staff.
                    </p>
                    @if($application->payment->paymongo_payment_id)
                        <p class="text-xs text-yellow-500 font-mono mt-2">
                            Ref: {{ $application->payment->paymongo_payment_id }}
                        </p>
                    @endif
                </div>

            {{-- Failed or No Payment Yet — Show Pay Button --}}
            @else

                {{-- Failed Notice --}}
                @if($application->payment?->isFailed())
                    <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-center">
                        <i class="bi bi-x-circle-fill text-red-500 text-2xl mb-1 block"></i>
                        <p class="font-semibold text-red-800 text-sm">Previous Payment Failed</p>
                        <p class="text-xs text-red-600 mt-1">You can try paying again below.</p>
                    </div>
                @endif

                {{-- ✅ PayMongo Pay Button --}}
                <form
                    action="{{ route('user.payment.submit', [
                        'userId'      => Auth::id(),
                        'application' => $application->id,
                    ]) }}"
                    method="POST"
                    id="paymentForm">
                    @csrf

                    <button
                        type="submit"
                        id="payBtn"
                        class="w-full flex items-center justify-center gap-3
                               bg-blue-600 hover:bg-blue-700 active:bg-blue-800
                               text-white font-bold py-4 rounded-xl transition
                               shadow-md hover:shadow-lg text-base
                               disabled:opacity-70 disabled:cursor-not-allowed">

                        {{-- Default State --}}
                        <span id="btnDefault" class="flex items-center gap-2">
                            <i class="bi bi-credit-card-2-front text-xl"></i>
                            Pay ₱{{ number_format($application->permit_fee, 2) }} via PayMongo
                        </span>

                        {{-- Loading State --}}
                        <span id="btnLoading" class="hidden items-center gap-2">
                            <svg class="animate-spin h-5 w-5"
                                 xmlns="http://www.w3.org/2000/svg"
                                 fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor"
                                      d="M4 12a8 8 0 018-8v8H4z"/>
                            </svg>
                            Connecting to PayMongo...
                        </span>
                    </button>
                </form>

                {{-- What Happens Next --}}
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 space-y-2">
                    <p class="text-xs font-semibold text-blue-700 uppercase tracking-wide">
                        What happens next?
                    </p>
                    @foreach([
                        'You\'ll be redirected to PayMongo\'s secure checkout',
                        'Choose your payment method (GCash, Maya, Card, etc.)',
                        'Complete the payment in your chosen app',
                        'You\'ll be redirected back here automatically',
                    ] as $step => $text)
                        <div class="flex items-start gap-2 text-sm text-blue-800">
                            <span class="inline-flex items-center justify-center w-5 h-5
                                         rounded-full bg-blue-200 text-blue-700 text-xs
                                         font-bold shrink-0 mt-0.5">
                                {{ $step + 1 }}
                            </span>
                            <span>{{ $text }}</span>
                        </div>
                    @endforeach
                </div>

                {{-- Accepted Payment Methods --}}
                <div class="text-center">
                    <p class="text-xs text-slate-400 mb-3">Accepted Payment Methods</p>
                    <div class="flex flex-wrap justify-center gap-2">
                        @foreach([
                            ['GCash',      'bg-blue-100 text-blue-700'],
                            ['Maya',       'bg-green-100 text-green-700'],
                            ['GrabPay',    'bg-emerald-100 text-emerald-700'],
                            ['Visa',       'bg-indigo-100 text-indigo-700'],
                            ['Mastercard', 'bg-red-100 text-red-700'],
                        ] as [$name, $color])
                            <span class="px-3 py-1.5 rounded-lg {{ $color }} text-xs font-semibold">
                                {{ $name }}
                            </span>
                        @endforeach
                    </div>
                </div>

                {{-- Security Badge --}}
                <div class="flex items-center justify-center gap-2 text-xs text-slate-400">
                    <i class="bi bi-lock-fill"></i>
                    <span>Secured and encrypted by PayMongo</span>
                </div>

            @endif

        </div>
    </div>
</div>

{{-- ✅ Loading state script --}}
<script>
    document.getElementById('paymentForm')?.addEventListener('submit', function () {
        const btn       = document.getElementById('payBtn');
        const defState  = document.getElementById('btnDefault');
        const loadState = document.getElementById('btnLoading');

        btn.disabled = true;
        defState.classList.add('hidden');
        loadState.classList.remove('hidden');
        loadState.classList.add('flex');
    });
</script>
@endsection