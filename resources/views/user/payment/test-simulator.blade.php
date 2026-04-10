{{-- resources/views/user/payments/test-simulator.blade.php --}}
@extends('layouts.app')
@section('title', 'Payment Simulator')

@section('content')
<div class="max-w-lg mx-auto">

    {{-- Back --}}
    <a href="{{ route('user.business.show', [
            'userId'      => Auth::id(),
            'application' => $application->id,
        ]) }}"
       class="inline-flex items-center gap-2 text-sm font-medium
              text-slate-600 hover:text-slate-800 mb-6 transition">
        <i class="bi bi-arrow-left"></i> Back to Application
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-blue-700 to-blue-900 px-8 py-8 text-center relative">
            {{-- TEST MODE Badge --}}
            <div class="absolute top-4 right-4">
                <span class="bg-orange-500 text-white text-xs font-bold
                             px-3 py-1 rounded-full tracking-widest uppercase">
                    TEST MODE
                </span>
            </div>

            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center
                        justify-center mx-auto mb-4">
                <i class="bi bi-credit-card-2-front text-white text-3xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-white">PayMongo Simulator</h1>
            <p class="text-blue-200 text-sm mt-1">
                {{ $application->application_number }}
            </p>
        </div>

        <div class="p-8 space-y-6">

            {{-- Payment Summary --}}
            <div class="bg-slate-50 rounded-xl p-5 space-y-3">
                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    Payment Details
                </h3>

                @foreach([
                    ['Business',    $application->business_name],
                    ['Description', $payment->description],
                    ['Reference',   $application->application_number],
                    ['Status',      strtoupper($payment->status)],
                ] as [$label, $value])
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500">{{ $label }}</span>
                        <span class="font-semibold text-slate-800">{{ $value }}</span>
                    </div>
                @endforeach

                <div class="border-t border-slate-200 pt-3 flex items-center justify-between">
                    <span class="font-bold text-slate-900 text-sm">Amount</span>
                    <span class="font-bold text-2xl text-blue-700">
                        ₱{{ number_format($payment->amount, 2) }}
                    </span>
                </div>
            </div>

            {{-- Info Banner --}}
            <div class="bg-orange-50 border border-orange-200 rounded-xl p-4
                        flex items-start gap-3">
                <i class="bi bi-info-circle-fill text-orange-500 text-lg mt-0.5 flex-shrink-0"></i>
                <div>
                    <p class="text-sm font-semibold text-orange-800">
                        Developer Simulator
                    </p>
                    <p class="text-xs text-orange-600 mt-1">
                        This page simulates PayMongo's checkout. Choose an outcome
                        below to test your payment flow. This only appears in
                        <strong>TEST MODE</strong>.
                    </p>
                </div>
            </div>

            {{-- Simulate Buttons --}}
            <div class="space-y-3">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">
                    Choose a Simulation
                </p>

                {{-- ✅ SUCCESS --}}
                <form action="{{ route('user.payment.test.simulate', [
                        'userId'      => Auth::id(),
                        'application' => $application->id,
                    ]) }}"
                    method="POST">
                    @csrf
                    <input type="hidden" name="action" value="paid">
                    <button type="submit"
                            onclick="return confirm('Simulate as SUCCESSFUL payment?')"
                            class="w-full flex items-center justify-between
                                   bg-green-600 hover:bg-green-700 text-white
                                   font-semibold py-4 px-5 rounded-xl transition
                                   shadow-sm group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-full flex
                                        items-center justify-center text-xl">
                                ✅
                            </div>
                            <div class="text-left">
                                <div class="font-bold">Simulate Successful</div>
                                <div class="text-green-200 text-xs">
                                    Payment marked as PAID
                                </div>
                            </div>
                        </div>
                        <i class="bi bi-arrow-right-circle text-xl
                                  group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </form>

                {{-- ❌ FAILED --}}
                <form action="{{ route('user.payment.test.simulate', [
                        'userId'      => Auth::id(),
                        'application' => $application->id,
                    ]) }}"
                    method="POST">
                    @csrf
                    <input type="hidden" name="action" value="failed">
                    <button type="submit"
                            onclick="return confirm('Simulate as FAILED payment?')"
                            class="w-full flex items-center justify-between
                                   bg-red-600 hover:bg-red-700 text-white
                                   font-semibold py-4 px-5 rounded-xl transition
                                   shadow-sm group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-full flex
                                        items-center justify-center text-xl">
                                ❌
                            </div>
                            <div class="text-left">
                                <div class="font-bold">Simulate Failed</div>
                                <div class="text-red-200 text-xs">
                                    Payment marked as FAILED
                                </div>
                            </div>
                        </div>
                        <i class="bi bi-arrow-right-circle text-xl
                                  group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </form>

                {{-- ⌛ EXPIRED --}}
                <form action="{{ route('user.payment.test.simulate', [
                        'userId'      => Auth::id(),
                        'application' => $application->id,
                    ]) }}"
                    method="POST">
                    @csrf
                    <input type="hidden" name="action" value="expired">
                    <button type="submit"
                            onclick="return confirm('Simulate as EXPIRED payment?')"
                            class="w-full flex items-center justify-between
                                   bg-slate-600 hover:bg-slate-700 text-white
                                   font-semibold py-4 px-5 rounded-xl transition
                                   shadow-sm group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-full flex
                                        items-center justify-center text-xl">
                                ⌛
                            </div>
                            <div class="text-left">
                                <div class="font-bold">Simulate Expired</div>
                                <div class="text-slate-300 text-xs">
                                    Payment link marked as EXPIRED
                                </div>
                            </div>
                        </div>
                        <i class="bi bi-arrow-right-circle text-xl
                                  group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </form>
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-center gap-2 text-xs text-slate-400 pt-2">
                <i class="bi bi-shield-lock-fill"></i>
                <span>PayMongo Test Environment · No real charges</span>
            </div>
        </div>
    </div>
</div>
@endsection