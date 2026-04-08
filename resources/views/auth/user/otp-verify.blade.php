@extends('layouts.auth-user')
@section('title', 'Verify Your Email')

@section('content')

    <div class="text-center mb-8">
        {{-- Email icon --}}
        <div class="inline-flex h-16 w-16 items-center justify-center rounded-full
                    bg-blue-100 text-blue-600 mb-4">
            <i class="bi bi-envelope-check text-3xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-slate-900">Check your email</h1>
        <p class="text-sm text-slate-500 mt-1">
            We sent a 6-digit OTP to your email address.
            <br>Enter it below to verify your account.
        </p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm
                    rounded-lg px-4 py-3 mb-5 text-center">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm
                    rounded-lg px-4 py-3 mb-5 text-center">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- OTP FORM --}}
    <form method="POST" action="{{ route('user.otp.verify') }}" class="space-y-5">
        @csrf

        <div>
            <label for="otp" class="block text-sm font-medium text-slate-700 mb-1 text-center">
                Enter 6-Digit OTP
            </label>
            {{-- Large centered OTP input --}}
            <input id="otp"
                   type="text"
                   name="otp"
                   maxlength="6"
                   placeholder="000000"
                   inputmode="numeric"
                   autocomplete="one-time-code"
                   class="w-full text-center text-3xl font-bold tracking-[0.5em] py-4 rounded-xl
                          border-2 {{ $errors->has('otp') ? 'border-red-400' : 'border-slate-300' }}
                          text-slate-900 placeholder:text-slate-300 placeholder:tracking-[0.5em]
                          focus:outline-none focus:border-blue-500 focus:ring-2
                          focus:ring-blue-500/30 transition" />
            @error('otp')
                <p class="text-red-500 text-xs mt-1 text-center">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white text-sm
                       font-semibold rounded-lg transition-colors focus:outline-none
                       focus:ring-2 focus:ring-offset-2 focus:ring-blue-600">
            Verify OTP
        </button>
    </form>

    {{-- RESEND OTP --}}
    {{-- Simple countdown timer using Alpine.js --}}
    <div class="text-center mt-5"
         x-data="{
             countdown: 60,
             canResend: false,
             start() {
                 let timer = setInterval(() => {
                     this.countdown--;
                     if (this.countdown <= 0) {
                         clearInterval(timer);
                         this.canResend = true;
                     }
                 }, 1000);
             }
         }"
         x-init="start()">

        <p class="text-sm text-slate-500">
            Didn't receive the code?
        </p>

        {{-- Show countdown while waiting --}}
        <p class="text-sm text-slate-400 mt-1" x-show="!canResend">
            Resend in <span class="font-semibold text-slate-600" x-text="countdown"></span> seconds
        </p>

        {{-- Show resend button when countdown is done --}}
        <form method="POST" action="{{ route('user.otp.resend') }}" x-show="canResend" x-cloak>
            @csrf
            <button type="submit"
                    class="text-sm text-blue-600 hover:text-blue-800 font-semibold hover:underline mt-1">
                Resend OTP
            </button>
        </form>
    </div>

@endsection