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

            {{-- Alerts --}}
            @if(session('error'))
                <div class="flex items-start gap-3 bg-red-50 border border-red-200
                            text-red-700 px-4 py-3 rounded-xl text-sm">
                    <i class="bi bi-exclamation-triangle-fill mt-0.5 shrink-0"></i>
                    <span>{{ session('error') }}</span>
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

            {{-- Already Paid --}}
            @if($application->payment && $application->payment->isPaid())
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

            @else
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
                        <span id="btnLoading" class="hidden flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg"
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

                {{-- What happens next --}}
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 space-y-2">
                    <p class="text-xs font-semibold text-blue-700 uppercase tracking-wide">
                        What happens next?
                    </p>
                    @foreach([
                        ['1', 'You\'ll be redirected to PayMongo\'s secure checkout page'],
                        ['2', 'Choose your preferred payment method'],
                        ['3', 'Complete the payment on your chosen app or card'],
                        ['4', 'You\'ll be redirected back here once done'],
                    ] as [$step, $text])
                        <div class="flex items-start gap-2 text-sm text-blue-800">
                            <span class="inline-flex items-center justify-center w-5 h-5
                                         rounded-full bg-blue-200 text-blue-700 text-xs
                                         font-bold shrink-0 mt-0.5">
                                {{ $step }}
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
                            ['GCash',      'bg-blue-100 text-blue-700',     'bi-phone'],
                            ['Maya',       'bg-green-100 text-green-700',   'bi-phone'],
                            ['GrabPay',    'bg-emerald-100 text-emerald-700','bi-phone'],
                            ['Visa',       'bg-indigo-100 text-indigo-700', 'bi-credit-card'],
                            ['Mastercard', 'bg-red-100 text-red-700',       'bi-credit-card'],
                        ] as [$name, $color, $icon])
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5
                                         rounded-lg {{ $color }} text-xs font-semibold">
                                <i class="bi {{ $icon }}"></i>
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

{{-- ✅ Loading state on form submit --}}
<script>
    document.getElementById('paymentForm')?.addEventListener('submit', function () {
        const btn       = document.getElementById('payBtn');
        const defState  = document.getElementById('btnDefault');
        const loadState = document.getElementById('btnLoading');

        btn.disabled = true;
        defState.classList.add('hidden');
        loadState.classList.remove('hidden');
    });
</script>
@endsection