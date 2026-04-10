@extends('layouts.auth-user')
@section('title', 'Reset Password')

@section('content')

    <div class="text-center mb-8">
        <div class="inline-flex h-16 w-16 items-center justify-center
                    rounded-full bg-blue-100 text-blue-600 mb-4">
            <i class="bi bi-shield-lock text-3xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-slate-900">Set a New Password</h1>
        <p class="text-sm text-slate-500 mt-1">
            Your new password must be at least 8 characters.
        </p>
    </div>

    {{-- Show validation errors --}}
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm
                    rounded-lg px-4 py-3 mb-5">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST"
          action="{{ route('user.password.update') }}"
          class="space-y-4">
        @csrf

        {{-- Hidden fields: token and email are passed from the URL --}}
        {{-- These are needed by the controller to verify and find the user --}}
        <input type="hidden" name="token" value="{{ $token }}" />
        <input type="hidden" name="email" value="{{ $email }}" />

        {{-- Email display (read only, for user reference) --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Email Address
            </label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <i class="bi bi-envelope"></i>
                </span>
                <input type="email"
                       value="{{ $email }}"
                       disabled
                       class="w-full pl-9 pr-4 py-2.5 text-sm rounded-lg border
                              border-slate-200 bg-slate-50 text-slate-500
                              cursor-not-allowed" />
            </div>
            <p class="text-xs text-slate-400 mt-1">
                Resetting password for this account.
            </p>
        </div>

        {{-- New Password --}}
        <div x-data="{ showPass: false }">
            <label for="password"
                   class="block text-sm font-medium text-slate-700 mb-1">
                New Password <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <i class="bi bi-lock"></i>
                </span>
                <input id="password"
                       :type="showPass ? 'text' : 'password'"
                       name="password"
                       placeholder="At least 8 characters"
                       class="w-full pl-9 pr-10 py-2.5 text-sm rounded-lg border
                              {{ $errors->has('password') ? 'border-red-400' : 'border-slate-300' }}
                              text-slate-900 placeholder:text-slate-400
                              focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />
                <button type="button"
                        @click="showPass = !showPass"
                        class="absolute inset-y-0 right-0 flex items-center
                               pr-3 text-slate-400 hover:text-slate-600">
                    <i :class="showPass ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                </button>
            </div>
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirm New Password --}}
        <div x-data="{ showPass: false }">
            <label for="password_confirmation"
                   class="block text-sm font-medium text-slate-700 mb-1">
                Confirm New Password <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <i class="bi bi-lock-fill"></i>
                </span>
                <input id="password_confirmation"
                       :type="showPass ? 'text' : 'password'"
                       name="password_confirmation"
                       placeholder="Repeat your new password"
                       class="w-full pl-9 pr-10 py-2.5 text-sm rounded-lg border
                              border-slate-300 text-slate-900 placeholder:text-slate-400
                              focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />
                <button type="button"
                        @click="showPass = !showPass"
                        class="absolute inset-y-0 right-0 flex items-center
                               pr-3 text-slate-400 hover:text-slate-600">
                    <i :class="showPass ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                </button>
            </div>
        </div>

        {{-- Password strength indicator --}}
        {{-- Simple Alpine.js reactive checker --}}
        {{-- <div x-data="{
                password: '',
                get strength() {
                    if (this.password.length === 0) return null;
                    if (this.password.length < 6)   return 'weak';
                    if (this.password.length < 10)  return 'fair';
                    return 'strong';
                },
                get strengthLabel() {
                    return { weak: 'Weak', fair: 'Fair', strong: 'Strong' }[this.strength] || '';
                },
                get strengthColor() {
                    return {
                        weak:   'bg-red-500',
                        fair:   'bg-yellow-500',
                        strong: 'bg-green-500'
                    }[this.strength] || '';
                },
                get barWidth() {
                    return { weak: 'w-1/3', fair: 'w-2/3', strong: 'w-full' }[this.strength] || 'w-0';
                }
            }"
             @keyup.window="
                const input = document.getElementById('password');
                if (input) password = input.value;
             ">
            <template x-if="strength">
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs text-slate-400">Password strength</span>
                        <span class="text-xs font-semibold"
                              :class="{
                                'text-red-500':   strength === 'weak',
                                'text-yellow-500': strength === 'fair',
                                'text-green-600': strength === 'strong'
                              }"
                              x-text="strengthLabel">
                        </span>
                    </div>
                    <div class="h-1.5 bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-300"
                             :class="[strengthColor, barWidth]">
                        </div>
                    </div>
                </div>
            </template>
        </div> --}}

        {{-- Submit --}}
        <button type="submit"
                class="w-full py-2.5 px-4 bg-slate-900 hover:bg-slate-700 text-white
                       text-sm font-semibold rounded-lg transition-colors focus:outline-none
                       focus:ring-2 focus:ring-offset-2 focus:ring-slate-900 mt-2">
            <i class="bi bi-check-circle mr-1"></i>
            Reset Password
        </button>

    </form>

    {{-- Back to login --}}
    <p class="text-center text-sm text-slate-500 mt-6">
        Remember your password?
        <a href="{{ route('user.login') }}"
           class="text-blue-600 font-semibold hover:underline">
            Back to Sign In
        </a>
    </p>

@endsection