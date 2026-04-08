@extends('layouts.app')

@section('title', 'My Profile')

@section('content')

{{-- Page Header --}}
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">My Profile</h1>
    <p class="text-sm text-slate-500 mt-1">Manage your account information and security settings.</p>
</div>

{{-- Tab State via Alpine --}}
<div x-data="{ activeTab: '{{ session('tab', 'info') }}' }">

    {{-- ───── Tab Navigation ───── --}}
    <div class="flex flex-wrap gap-1 bg-white border border-slate-200 rounded-xl p-1 w-fit mb-6 shadow-sm">

        {{-- Profile Info Tab --}}
        <button
            @click="activeTab = 'info'"
            :class="activeTab === 'info'
                ? 'bg-[#1e2a4a] text-white shadow'
                : 'text-slate-500 hover:text-slate-700 hover:bg-slate-100'"
            class="flex items-center gap-2 px-5 py-2 rounded-lg text-sm font-medium transition-all duration-200">
            <i class="bi bi-person-fill"></i>
            Profile Info
        </button>

        {{-- Change Password Tab --}}
        <button
            @click="activeTab = 'password'"
            :class="activeTab === 'password'
                ? 'bg-[#1e2a4a] text-white shadow'
                : 'text-slate-500 hover:text-slate-700 hover:bg-slate-100'"
            class="flex items-center gap-2 px-5 py-2 rounded-lg text-sm font-medium transition-all duration-200">
            <i class="bi bi-shield-lock-fill"></i>
            Change Password
        </button>

        {{-- Delete Account Tab --}}
        <button
            @click="activeTab = 'delete'"
            :class="activeTab === 'delete'
                ? 'bg-red-600 text-white shadow'
                : 'text-red-500 hover:text-red-700 hover:bg-red-50'"
            class="flex items-center gap-2 px-5 py-2 rounded-lg text-sm font-medium transition-all duration-200">
            <i class="bi bi-trash3-fill"></i>
            Delete Account
        </button>

    </div>

    {{-- ───────────────────────────────────────── --}}
    {{--  TAB 1 — Profile Information              --}}
    {{-- ───────────────────────────────────────── --}}
    <div x-show="activeTab === 'info'" x-cloak>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Avatar Card --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col items-center text-center">

                <div class="w-24 h-24 rounded-full bg-[#1e2a4a] flex items-center justify-center text-white text-3xl font-bold mb-4 shadow-md">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                <h2 class="text-lg font-bold text-slate-800">{{ $user->name }}</h2>
                <p class="text-sm text-slate-500 mt-1">{{ $user->email }}</p>

                <span class="mt-3 inline-flex items-center gap-1 bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full">
                    <i class="bi bi-person-badge"></i>
                    {{ ucfirst($user->role ?? 'User') }}
                </span>

                <div class="mt-5 w-full border-t border-slate-100 pt-4">
                    <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">Member Since</p>
                    <p class="text-sm font-medium text-slate-700">
                        {{ $user->created_at->format('F d, Y') }}
                    </p>
                </div>

                <div class="mt-3 w-full border-t border-slate-100 pt-4">
                    <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">Last Updated</p>
                    <p class="text-sm font-medium text-slate-700">
                        {{ $user->updated_at->format('F d, Y') }}
                    </p>
                </div>

            </div>

            {{-- Edit Info Form --}}
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                    <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center">
                        <i class="bi bi-pencil-square text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Personal Information</h3>
                        <p class="text-xs text-slate-400">Update your name and contact details.</p>
                    </div>
                </div>

                <form action="{{ route('user.profile.info', ['userId' => auth()->id()]) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    {{-- Name --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <i class="bi bi-person"></i>
                            </span>
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                placeholder="Enter your full name"
                                class="w-full pl-9 pr-4 py-2.5 border rounded-lg text-sm text-slate-700
                                       focus:outline-none focus:ring-2 focus:ring-[#1e2a4a] focus:border-transparent
                                       {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}"
                            />
                        </div>
                        @error('name')
                            <p class="text-xs text-red-500 mt-1 flex items-center gap-1">
                                <i class="bi bi-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Email (readonly) --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Email Address
                            <span class="ml-1 inline-flex items-center gap-1 text-xs text-slate-400 font-normal">
                                <i class="bi bi-lock-fill"></i> Cannot be changed
                            </span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input
                                type="email"
                                value="{{ $user->email }}"
                                readonly
                                class="w-full pl-9 pr-10 py-2.5 border border-slate-200 rounded-lg text-sm
                                       text-slate-400 bg-slate-50 cursor-not-allowed select-none"
                            />
                            <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-300">
                                <i class="bi bi-lock"></i>
                            </span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                            <i class="bi bi-info-circle"></i>
                            Contact support if you need to update your email address.
                        </p>
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Phone Number
                            <span class="text-slate-400 font-normal">(optional)</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <i class="bi bi-telephone"></i>
                            </span>
                            <input
                                type="text"
                                name="phone"
                                value="{{ old('phone', $user->phone ?? '') }}"
                                placeholder="e.g. +63 912 345 6789"
                                class="w-full pl-9 pr-4 py-2.5 border rounded-lg text-sm text-slate-700
                                       focus:outline-none focus:ring-2 focus:ring-[#1e2a4a] focus:border-transparent
                                       {{ $errors->has('phone') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}"
                            />
                        </div>
                        @error('phone')
                            <p class="text-xs text-red-500 mt-1 flex items-center gap-1">
                                <i class="bi bi-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <div class="pt-2 flex items-center gap-3">
                        <button
                            type="submit"
                            class="flex items-center gap-2 bg-[#1e2a4a] hover:bg-[#16213a] text-white
                                   text-sm font-semibold px-6 py-2.5 rounded-lg transition-colors duration-200 shadow">
                            <i class="bi bi-floppy"></i>
                            Save Changes
                        </button>
                        <a href="{{ route('user.dashboard', ['userId' => auth()->id()]) }}"
                           class="text-sm text-slate-500 hover:text-slate-700 transition-colors">
                            Cancel
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- ───────────────────────────────────────── --}}
    {{--  TAB 2 — Change Password                  --}}
    {{-- ───────────────────────────────────────── --}}
    <div x-show="activeTab === 'password'" x-cloak>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Tips Card --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 h-fit">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-9 h-9 rounded-lg bg-amber-50 flex items-center justify-center">
                        <i class="bi bi-lightbulb-fill text-amber-500"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">Password Tips</h3>
                </div>
                <ul class="space-y-3 text-sm text-slate-600">
                    <li class="flex items-start gap-2">
                        <i class="bi bi-check-circle-fill text-green-500 mt-0.5 flex-shrink-0"></i>
                        At least <strong>8 characters</strong> long
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="bi bi-check-circle-fill text-green-500 mt-0.5 flex-shrink-0"></i>
                        Mix of <strong>uppercase & lowercase</strong> letters
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="bi bi-check-circle-fill text-green-500 mt-0.5 flex-shrink-0"></i>
                        At least one <strong>number</strong>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="bi bi-check-circle-fill text-green-500 mt-0.5 flex-shrink-0"></i>
                        Avoid using <strong>personal info</strong>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="bi bi-check-circle-fill text-green-500 mt-0.5 flex-shrink-0"></i>
                        Don't reuse <strong>old passwords</strong>
                    </li>
                </ul>
                <div class="mt-5 bg-amber-50 border border-amber-200 rounded-lg p-3">
                    <p class="text-xs text-amber-700 flex items-start gap-1.5">
                        <i class="bi bi-shield-exclamation mt-0.5 flex-shrink-0"></i>
                        You will remain logged in after changing your password.
                    </p>
                </div>
            </div>

            {{-- Change Password Form --}}
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm p-6"
                 x-data="{
                     showCurrent: false,
                     showNew: false,
                     showConfirm: false,
                     newPass: '',
                     strength: 0,
                     get strengthLabel() {
                         if (this.strength === 0) return '';
                         if (this.strength <= 1) return 'Weak';
                         if (this.strength === 2) return 'Fair';
                         if (this.strength === 3) return 'Good';
                         return 'Strong';
                     },
                     get strengthColor() {
                         if (this.strength <= 1) return 'bg-red-400';
                         if (this.strength === 2) return 'bg-amber-400';
                         if (this.strength === 3) return 'bg-blue-400';
                         return 'bg-green-500';
                     },
                     checkStrength(val) {
                         this.newPass = val;
                         let s = 0;
                         if (val.length >= 8) s++;
                         if (/[A-Z]/.test(val) && /[a-z]/.test(val)) s++;
                         if (/[0-9]/.test(val)) s++;
                         if (/[^A-Za-z0-9]/.test(val)) s++;
                         this.strength = s;
                     }
                 }">

                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                    <div class="w-9 h-9 rounded-lg bg-purple-50 flex items-center justify-center">
                        <i class="bi bi-shield-lock text-purple-600"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Change Password</h3>
                        <p class="text-xs text-slate-400">Keep your account secure with a strong password.</p>
                    </div>
                </div>

                <form action="{{ route('user.profile.password',['userId' => auth()->id()]) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    {{-- Current Password --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Current Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input
                                :type="showCurrent ? 'text' : 'password'"
                                name="current_password"
                                placeholder="Enter your current password"
                                class="w-full pl-9 pr-10 py-2.5 border rounded-lg text-sm text-slate-700
                                       focus:outline-none focus:ring-2 focus:ring-[#1e2a4a] focus:border-transparent
                                       {{ $errors->has('current_password') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}"
                            />
                            <button type="button" @click="showCurrent = !showCurrent"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600">
                                <i :class="showCurrent ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="text-xs text-red-500 mt-1 flex items-center gap-1">
                                <i class="bi bi-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- New Password --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            New Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <i class="bi bi-lock-fill"></i>
                            </span>
                            <input
                                :type="showNew ? 'text' : 'password'"
                                name="password"
                                placeholder="Enter a strong new password"
                                @input="checkStrength($event.target.value)"
                                class="w-full pl-9 pr-10 py-2.5 border rounded-lg text-sm text-slate-700
                                       focus:outline-none focus:ring-2 focus:ring-[#1e2a4a] focus:border-transparent
                                       {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}"
                            />
                            <button type="button" @click="showNew = !showNew"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600">
                                <i :class="showNew ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                            </button>
                        </div>

                        {{-- Strength Meter --}}
                        <div x-show="newPass.length > 0" class="mt-2 space-y-1">
                            <div class="flex gap-1 h-1.5">
                                <div class="flex-1 rounded-full transition-all duration-300"
                                     :class="strength >= 1 ? strengthColor : 'bg-slate-200'"></div>
                                <div class="flex-1 rounded-full transition-all duration-300"
                                     :class="strength >= 2 ? strengthColor : 'bg-slate-200'"></div>
                                <div class="flex-1 rounded-full transition-all duration-300"
                                     :class="strength >= 3 ? strengthColor : 'bg-slate-200'"></div>
                                <div class="flex-1 rounded-full transition-all duration-300"
                                     :class="strength >= 4 ? strengthColor : 'bg-slate-200'"></div>
                            </div>
                            <p class="text-xs font-medium"
                               :class="{
                                   'text-red-500': strength <= 1,
                                   'text-amber-500': strength === 2,
                                   'text-blue-500': strength === 3,
                                   'text-green-600': strength >= 4
                               }">
                                Password strength: <span x-text="strengthLabel"></span>
                            </p>
                        </div>

                        @error('password')
                            <p class="text-xs text-red-500 mt-1 flex items-center gap-1">
                                <i class="bi bi-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Confirm New Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <i class="bi bi-lock-fill"></i>
                            </span>
                            <input
                                :type="showConfirm ? 'text' : 'password'"
                                name="password_confirmation"
                                placeholder="Re-enter your new password"
                                class="w-full pl-9 pr-10 py-2.5 border border-slate-300 rounded-lg text-sm text-slate-700
                                       focus:outline-none focus:ring-2 focus:ring-[#1e2a4a] focus:border-transparent"
                            />
                            <button type="button" @click="showConfirm = !showConfirm"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600">
                                <i :class="showConfirm ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="pt-2 flex items-center gap-3">
                        <button type="submit"
                                class="flex items-center gap-2 bg-[#1e2a4a] hover:bg-[#16213a] text-white
                                       text-sm font-semibold px-6 py-2.5 rounded-lg transition-colors duration-200 shadow">
                            <i class="bi bi-shield-check"></i>
                            Update Password
                        </button>
                        <button type="reset"
                                class="text-sm text-slate-500 hover:text-slate-700 transition-colors">
                            Clear Fields
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- ───────────────────────────────────────── --}}
    {{--  TAB 3 — Delete Account                   --}}
    {{-- ───────────────────────────────────────── --}}
    <div x-show="activeTab === 'delete'" x-cloak>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6"
         x-data="{
             showPassword: false,
             confirmed: false,
             modalOpen: false,
             confirmPassword: '',
         }">

        {{-- Warning Info Card --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 h-fit">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-9 h-9 rounded-lg bg-red-50 flex items-center justify-center">
                    <i class="bi bi-exclamation-triangle-fill text-red-500"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-800">What happens</h3>
            </div>
            <ul class="space-y-3 text-sm text-slate-600">
                <li class="flex items-start gap-2">
                    <i class="bi bi-x-circle-fill text-red-400 mt-0.5 flex-shrink-0"></i>
                    You will be <strong>logged out immediately</strong>
                </li>
                <li class="flex items-start gap-2">
                    <i class="bi bi-x-circle-fill text-red-400 mt-0.5 flex-shrink-0"></i>
                    Your account will be <strong>deactivated</strong>
                </li>
                <li class="flex items-start gap-2">
                    <i class="bi bi-x-circle-fill text-red-400 mt-0.5 flex-shrink-0"></i>
                    You <strong>cannot log in</strong> while deactivated
                </li>
                <li class="flex items-start gap-2">
                    <i class="bi bi-check-circle-fill text-green-500 mt-0.5 flex-shrink-0"></i>
                    Your <strong>data is retained</strong> in our system
                </li>
                <li class="flex items-start gap-2">
                    <i class="bi bi-check-circle-fill text-green-500 mt-0.5 flex-shrink-0"></i>
                    Account can be <strong>restored by support</strong>
                </li>
            </ul>

            <div class="mt-5 bg-blue-50 border border-blue-200 rounded-lg p-3">
                <p class="text-xs text-blue-700 flex items-start gap-1.5">
                    <i class="bi bi-info-circle-fill mt-0.5 flex-shrink-0"></i>
                    To reactivate your account, contact our support team with your registered email.
                </p>
            </div>
        </div>

        {{-- Delete Account Form --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-red-200 shadow-sm p-6">

            {{-- Header --}}
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-red-100">
                <div class="w-9 h-9 rounded-lg bg-red-50 flex items-center justify-center">
                    <i class="bi bi-trash3 text-red-600"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-red-700">Delete Account</h3>
                    <p class="text-xs text-slate-400">Your account will be deactivated and you will be logged out.</p>
                </div>
            </div>

            {{-- Danger Banner --}}
            <div class="flex items-start gap-3 bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                <i class="bi bi-exclamation-octagon-fill text-red-500 text-lg flex-shrink-0 mt-0.5"></i>
                <div>
                    <p class="text-sm font-semibold text-red-700">You are about to deactivate your account</p>
                    <p class="text-xs text-red-600 mt-1">
                        Your account will be <strong>deactivated immediately</strong> and you will be logged out.
                        Your data is retained and can be restored — contact support to reactivate your account.
                    </p>
                </div>
            </div>

            

            {{-- Password Field — bound to Alpine confirmPassword --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Confirm Your Password <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input
                        :type="showPassword ? 'text' : 'password'"
                        x-model="confirmPassword"
                        placeholder="Enter your password to confirm"
                        class="w-full pl-9 pr-10 py-2.5 border rounded-lg text-sm text-slate-700
                               focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent
                               {{ $errors->has('confirm_password') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}"
                    />
                    <button type="button" @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600">
                        <i :class="showPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                    </button>
                </div>
                @error('confirm_password')
                    <p class="text-xs text-red-500 mt-1 flex items-center gap-1">
                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Checkbox Confirmation --}}
            <div class="mb-5">
                <label class="flex items-start gap-3 cursor-pointer group">
                    <input
                        type="checkbox"
                        x-model="confirmed"
                        class="mt-0.5 w-4 h-4 accent-red-600 cursor-pointer flex-shrink-0"
                    />
                    <span class="text-sm text-slate-600 group-hover:text-slate-800 transition-colors">
                        I understand that my account will be <strong>deactivated immediately</strong>
                        and I will be logged out. I can contact support to reactivate my account.
                    </span>
                </label>
            </div>

            {{-- Delete Button --}}
            <button
                type="button"
                @click="modalOpen = true"
                :disabled="!confirmed || confirmPassword.trim() === ''"
                :class="confirmed && confirmPassword.trim() !== ''
                        ? 'bg-red-600 hover:bg-red-700 cursor-pointer shadow'
                        : 'bg-red-200 cursor-not-allowed pointer-events-none'"
                class="flex items-center gap-2 text-white text-sm font-semibold
                       px-6 py-2.5 rounded-lg transition-colors duration-200">
                <i class="bi bi-trash3-fill"></i>
                Deactivate My Account
            </button>

            <p class="text-xs text-slate-400 mt-3 flex items-center gap-1">
                <i class="bi bi-info-circle"></i>
                The button activates only after checking the box and entering your password.
            </p>

            {{-- ── Confirmation Modal ── --}}
            <div
                x-show="modalOpen"
                x-cloak
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4">

                <div
                    x-show="modalOpen"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6"
                    @click.outside="modalOpen = false">

                    {{-- Modal Icon --}}
                    <div class="flex justify-center mb-4">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="bi bi-trash3-fill text-red-600 text-2xl"></i>
                        </div>
                    </div>

                    {{-- Modal Title --}}
                    <h3 class="text-center text-lg font-bold text-slate-800 mb-1">
                        Final Confirmation
                    </h3>
                    <p class="text-center text-sm text-slate-500 mb-6">
                        Are you absolutely sure you want to deactivate your account?
                        You will be <span class="text-red-600 font-semibold">logged out immediately</span>
                        and will no longer be able to sign in. Contact support to reactivate.
                    </p>

                    {{-- Actual Delete Form --}}
                    <form action="{{ route('user.profile.delete',['userId' => auth()->id()]) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        {{-- Password passed via x-model Alpine data --}}
                        <input
                            type="hidden"
                            name="confirm_password"
                            x-model="confirmPassword"
                        />

                        <div class="flex flex-col gap-3">
                            <button type="submit"
                                    class="w-full flex items-center justify-center gap-2 bg-red-600
                                           hover:bg-red-700 text-white text-sm font-semibold
                                           py-2.5 rounded-lg transition-colors duration-200 shadow">
                                <i class="bi bi-trash3-fill"></i>
                                Yes, Deactivate My Account
                            </button>

                            <button type="button"
                                    @click="modalOpen = false"
                                    class="w-full flex items-center justify-center gap-2 bg-slate-100
                                           hover:bg-slate-200 text-slate-700 text-sm font-semibold
                                           py-2.5 rounded-lg transition-colors duration-200">
                                <i class="bi bi-arrow-left"></i>
                                Cancel, Keep My Account
                            </button>
                        </div>
                    </form>

                </div>
            </div>
            {{-- ── End Modal ── --}}

        </div>
    </div>
</div>

</div>

@endsection