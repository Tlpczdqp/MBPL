{{-- resources/views/user/payments/cancel.blade.php --}}
@extends('layouts.app')
@section('title', 'Payment Cancelled')

@section('content')
<div class="max-w-lg mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-slate-500 to-slate-700 px-8 py-10 text-center">
            <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="bi bi-x-circle-fill text-white text-5xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-white">Payment Cancelled</h1>
            <p class="text-slate-200 text-sm mt-1">
                {{ $application->application_number }}
            </p>
        </div>

        <div class="p-8 space-y-6">

            <div class="bg-slate-50 rounded-xl p-5 text-center">
                <p class="text-slate-600 text-sm">
                    You cancelled the payment for
                    <span class="font-semibold text-slate-800">
                        {{ $application->business_name }}
                    </span>.
                    <br>
                    You can try again anytime.
                </p>
            </div>

            <div class="flex flex-col gap-3">
                {{-- Try again --}}
                <form action="{{ route('user.payment.submit', [
                        'userId'      => Auth::id(),
                        'application' => $application->id,
                    ]) }}"
                    method="POST">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2
                                   bg-blue-700 hover:bg-blue-800 text-white
                                   font-bold py-3 rounded-xl transition text-sm">
                        <i class="bi bi-arrow-clockwise"></i>
                        Try Payment Again
                    </button>
                </form>

                {{-- Back to Application --}}
                <a href="{{ route('user.business.show', [
                        'userId'      => Auth::id(),
                        'application' => $application->id,
                    ]) }}"
                   class="w-full flex items-center justify-center gap-2
                          border border-slate-200 text-slate-600
                          font-medium py-3 rounded-xl hover:bg-slate-50 transition text-sm">
                    <i class="bi bi-arrow-left"></i>
                    Back to Application
                </a>
            </div>
        </div>
    </div>
</div>
@endsection