@extends('layouts.app')
@section('title', 'My Profile')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Page Header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-900">My Profile</h1>
        <p class="text-sm text-slate-500 mt-1">
            Manage your account information and password.
        </p>
    </div>

    {{-- ── PROFILE INFO CARD ─────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        {{-- Card Header --}}
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
            <div class="h-10 w-10 rounded-xl bg-blue-100 flex items-center justify-center">
                <i class="bi bi-person text-blue-600 text-lg"></i>
            </div>
            <div>
                <h2 class="text-base font-semibold text-slate-900">Personal Information</h2>
                <p class="text-xs text-slate-400">Update your name, email and phone number.</p>
            </div>
        </div>

        {{-- Profile Form --}}
        <div class="p-6">

            {{-- Avatar / Name display --}}
            <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-100">
                <div class="h-16 w-16 rounded-full flex-shrink-0
                            flex items-center justify-center text-xl font-bold
                            {{ $user->avatar ? '' : 'bg-blue-600 text-white' }}">
                    @if($user->avatar)
                        <img src="{{ $user->avatar }}"
                             alt="Avatar"
                             class="h-16 w-16 rounded-full object-cover" />
                    @else
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    @endif
                </div>
                <div>
                    <p class="text-lg font-bold text-slate-900">{{ $user->name }}</p>
                    <p class="text-sm text-slate-500">{{ $user->email }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        {{-- Verified badge --}}
                        @if($user->email_verified)
                            <span class="inline-flex items-center gap-1 text-xs
                                         text-green-700 bg-green-100 px-2 py-0.5 rounded-full font-medium">
                                <i class="bi bi-patch-check-fill text-xs"></i>
                                Verified
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-xs
                                         text-red-700 bg-red-100 px-2 py-0.5 rounded-full font-medium">
                                <i class="bi bi-exclamation-circle text-xs"></i>
                                Not Verified
                            </span>
                        @endif

                        {{-- Social login badge --}}
                        @if($user->google_id)
                            <span class="inline-flex items-center gap-1 text-xs
                                         text-blue-700 bg-blue-100 px-2 py-0.5 rounded-full font-medium">
                                <i class="bi bi-google text-xs"></i>
                                Google
                            </span>
                        @endif
                        @if($user->facebook_id)
                            <span class="inline-flex items-center gap-1 text-xs
                                         text-blue-700 bg-blue-100 px-2 py-0.5 rounded-full font-medium">
                                <i class="bi bi-facebook text-xs"></i>
                                Facebook
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Success / Error messages --}}
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700
                            text-sm rounded-lg px-4 py-3 mb-5 flex items-center gap-2">
                    <i class="bi bi-check-circle-fill"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700
                            text-sm rounded-lg px-4 py-3 mb-5">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{-- Update Info Form --}}
            <form method="POST"
                  action="{{ route('user.profile.update', ['userId' => $user->id]) }}"
                  class="space-y-4">
                @csrf
                @method('PUT')

                {{-- Name --}}
                <div>
                    <label for="name"
                           class="block text-sm font-medium text-slate-700 mb-1">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center
                                     pl-3 text-slate-400">
                            <i class="bi bi-person"></i>
                        </span>
                        <input id="name"
                               type="text"
                               name="name"
                               value="{{ old('name', $user->name) }}"
                               class="w-full pl-9 pr-4 py-2.5 text-sm rounded-lg border
                                      {{ $errors->has('name') ? 'border-red-400' : 'border-slate-300' }}
                                      text-slate-900 focus:outline-none
                                      focus:ring-2 focus:ring-blue-500 transition" />
                    </div>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email"
                           class="block text-sm font-medium text-slate-700 mb-1">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center
                                     pl-3 text-slate-400">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input id="email"
                               type="email"
                               name="email"
                               value="{{ old('email', $user->email) }}"
                               class="w-full pl-9 pr-4 py-2.5 text-sm rounded-lg border
                                      {{ $errors->has('email') ? 'border-red-400' : 'border-slate-300' }}
                                      text-slate-900 focus:outline-none
                                      focus:ring-2 focus:ring-blue-500 transition" />
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Phone --}}
                <div>
                    <label for="phone"
                           class="block text-sm font-medium text-slate-700 mb-1">
                        Phone Number
                        <span class="text-slate-400 font-normal">(optional)</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center
                                     pl-3 text-slate-400">
                            <i class="bi bi-telephone"></i>
                        </span>
                        <input id="phone"
                               type="tel"
                               name="phone"
                               value="{{ old('phone', $user->phone) }}"
                               placeholder="09XX-XXX-XXXX"
                               class="w-full pl-9 pr-4 py-2.5 text-sm rounded-lg
                                      border border-slate-300 text-slate-900
                                      focus:outline-none focus:ring-2
                                      focus:ring-blue-500 transition" />
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit"
                            class="px-6 py-2.5 bg-slate-900 hover:bg-slate-700 text-white
                                   text-sm font-semibold rounded-lg transition-colors">
                        <i class="bi bi-floppy mr-1"></i>
                        Save Changes
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- ── CHANGE PASSWORD CARD ──────────────────────────────── --}}
    {{-- Hide for social login users — they don't have a password --}}
    @if(!$user->google_id && !$user->facebook_id)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-amber-100 flex items-center justify-center">
                    <i class="bi bi-lock text-amber-600 text-lg"></i>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-slate-900">Change Password</h2>
                    <p class="text-xs text-slate-400">
                        Use a strong password of at least 8 characters.
                    </p>
                </div>
            </div>

            <div class="p-6">
                <form method="POST"
                      action="{{ route('user.profile.password', ['userId' => $user->id]) }}"
                      class="space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Current Password --}}
                    <div x-data="{ showPass: false }">
                        <label for="current_password"
                               class="block text-sm font-medium text-slate-700 mb-1">
                            Current Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center
                                         pl-3 text-slate-400">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input id="current_password"
                                   :type="showPass ? 'text' : 'password'"
                                   name="current_password"
                                   placeholder="Your current password"
                                   class="w-full pl-9 pr-10 py-2.5 text-sm rounded-lg border
                                          {{ $errors->has('current_password') ? 'border-red-400' : 'border-slate-300' }}
                                          text-slate-900 focus:outline-none
                                          focus:ring-2 focus:ring-blue-500 transition" />
                            <button type="button"
                                    @click="showPass = !showPass"
                                    class="absolute inset-y-0 right-0 flex items-center
                                           pr-3 text-slate-400 hover:text-slate-600">
                                <i :class="showPass ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- New Password --}}
                    <div x-data="{ showPass: false }">
                        <label for="password"
                               class="block text-sm font-medium text-slate-700 mb-1">
                            New Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center
                                         pl-3 text-slate-400">
                                <i class="bi bi-lock-fill"></i>
                            </span>
                            <input id="password"
                                   :type="showPass ? 'text' : 'password'"
                                   name="password"
                                   placeholder="At least 8 characters"
                                   class="w-full pl-9 pr-10 py-2.5 text-sm rounded-lg border
                                          {{ $errors->has('password') ? 'border-red-400' : 'border-slate-300' }}
                                          text-slate-900 focus:outline-none
                                          focus:ring-2 focus:ring-blue-500 transition" />
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
                            <span class="absolute inset-y-0 left-0 flex items-center
                                         pl-3 text-slate-400">
                                <i class="bi bi-lock-fill"></i>
                            </span>
                            <input id="password_confirmation"
                                   :type="showPass ? 'text' : 'password'"
                                   name="password_confirmation"
                                   placeholder="Repeat new password"
                                   class="w-full pl-9 pr-10 py-2.5 text-sm rounded-lg
                                          border border-slate-300 text-slate-900
                                          focus:outline-none focus:ring-2
                                          focus:ring-blue-500 transition" />
                            <button type="button"
                                    @click="showPass = !showPass"
                                    class="absolute inset-y-0 right-0 flex items-center
                                           pr-3 text-slate-400 hover:text-slate-600">
                                <i :class="showPass ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit"
                                class="px-6 py-2.5 bg-amber-600 hover:bg-amber-700 text-white
                                       text-sm font-semibold rounded-lg transition-colors">
                            <i class="bi bi-shield-check mr-1"></i>
                            Update Password
                        </button>
                    </div>

                </form>
            </div>
        </div>
    @endif

    {{-- ── ACCOUNT STATS ─────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <h2 class="text-base font-semibold text-slate-900 mb-4">Account Summary</h2>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">
                    Member Since
                </p>
                <p class="text-slate-900 font-medium mt-0.5">
                    {{ $user->created_at->format('F d, Y') }}
                </p>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">
                    Total Applications
                </p>
                <p class="text-slate-900 font-bold mt-0.5 text-lg">
                    {{ $applicationCount }}
                </p>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">
                    Email Status
                </p>
                <p class="mt-0.5">
                    @if($user->email_verified)
                        <span class="text-green-600 font-medium">Verified ✓</span>
                    @else
                        <span class="text-red-500 font-medium">Not Verified</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">
                    Login Method
                </p>
                <p class="text-slate-900 font-medium mt-0.5">
                    @if($user->google_id)
                        Google
                    @elseif($user->facebook_id)
                        Facebook
                    @else
                        Email & Password
                    @endif
                </p>
            </div>
        </div>
    </div>

</div>
@endsection