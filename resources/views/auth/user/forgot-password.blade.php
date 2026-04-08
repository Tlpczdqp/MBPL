@extends('layouts.auth-user')
@section('title', 'Forgot Password')

@section('content')

    <div class="text-center mb-8">
        <div class="inline-flex h-16 w-16 items-center justify-center
                    rounded-full bg-slate-100 text-slate-600 mb-4">
            <i class="bi bi-key text-3xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-slate-900">Forgot your password?</h1>
        <p class="text-sm text-slate-500 mt-1">
            Enter your registered email and we'll send you a reset link.
        </p>
    </div>

    {{-- SUCCESS MESSAGE --}}
    {{-- session('status') is set in ForgotPasswordController::sendResetLink() --}}
    @if(session('status'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm
                    rounded-lg px-4 py-4 mb-5 text-center">
            <i class="bi bi-envelope-check-fill text-xl block mb-1"></i>
            <p class="font-semibold">Email Sent!</p>
            <p class="mt-1 text-green-600">{{ session('status') }}</p>
        </div>
    @endif

    {{-- ERROR MESSAGES --}}
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm
                    rounded-lg px-4 py-3 mb-5">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST"
          action="{{ route('user.password.email') }}"
          class="space-y-4">
        @csrf

        <div>
            <label for="email"
                   class="block text-sm font-medium text-slate-700 mb-1">
                Email Address
            </label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <i class="bi bi-envelope"></i>
                </span>
                <input id="email"
                       type="email"
                       name="email"
                       value="{{ old('email') }}"
                       placeholder="you@example.com"
                       class="w-full pl-9 pr-4 py-2.5 text-sm rounded-lg border
                              {{ $errors->has('email') ? 'border-red-400' : 'border-slate-300' }}
                              text-slate-900 placeholder:text-slate-400
                              focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />
            </div>
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="w-full py-2.5 px-4 bg-slate-900 hover:bg-slate-700 text-white
                       text-sm font-semibold rounded-lg transition-colors focus:outline-none
                       focus:ring-2 focus:ring-offset-2 focus:ring-slate-900">
            <i class="bi bi-send mr-1"></i>
            Send Reset Link
        </button>
    </form>

    <p class="text-center text-sm text-slate-500 mt-6">
        Remember your password?
        <a href="{{ route('user.login') }}"
           class="text-blue-600 font-semibold hover:underline">
            Back to Sign In
        </a>
    </p>

@endsection