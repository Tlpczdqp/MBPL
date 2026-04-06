@extends('layouts.app')

@section('title', 'Renew Business Permit')

@section('content')

<div x-data="{
    step: 1,
    totalSteps: 4,
    businessType: '{{ old('business_type', $application->business_type) }}',
    billingFrequency: '{{ old('billing_frequency', $application->billing_frequency ?? '') }}',
    businessActivity: '{{ old('business_activity', $application->business_activity ?? '') }}',
    next() { if (this.step < this.totalSteps) this.step++ },
    prev() { if (this.step > 1) this.step-- },
}">

    {{-- ── Page Header ── --}}
    <div class="flex items-start justify-between mb-2">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Renew Business Permit</h1>
            <p class="text-sm text-blue-600 mt-0.5">
                Fill in the details below to submit your business permit renewal.
            </p>
        </div>
        <a href="{{ route('user.business.index', ['userId' => auth()->id()]) }}"
           class="text-sm text-slate-500 hover:text-slate-700 flex items-center gap-1.5 mt-1 transition-colors">
            <i class="bi bi-arrow-left"></i> Back to Applications
        </a>
    </div>

    {{-- ── Step Indicator ── --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm px-6 py-4 mb-4">
        <div class="flex items-center">

            {{-- Step 1 --}}
            <div class="flex items-center gap-2 flex-shrink-0">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                            border-2 transition-all duration-300"
                     :class="step > 1
                         ? 'bg-blue-600 border-blue-600 text-white'
                         : step === 1
                             ? 'bg-blue-600 border-blue-600 text-white'
                             : 'bg-white border-slate-300 text-slate-400'">
                    <span x-show="step > 1"><i class="bi bi-check-lg text-xs"></i></span>
                    <span x-show="step <= 1">1</span>
                </div>
                <span class="text-sm font-medium transition-colors"
                      :class="step >= 1 ? 'text-slate-800' : 'text-slate-400'">
                    Business Info
                </span>
            </div>

            {{-- Line 1→2 --}}
            <div class="flex-1 h-px mx-4 transition-all duration-500"
                 :class="step > 1 ? 'bg-blue-600' : 'bg-slate-200'"></div>

            {{-- Step 2 --}}
            <div class="flex items-center gap-2 flex-shrink-0">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                            border-2 transition-all duration-300"
                     :class="step > 2
                         ? 'bg-blue-600 border-blue-600 text-white'
                         : step === 2
                             ? 'bg-blue-600 border-blue-600 text-white'
                             : 'bg-white border-slate-300 text-slate-400'">
                    <span x-show="step > 2"><i class="bi bi-check-lg text-xs"></i></span>
                    <span x-show="step <= 2">2</span>
                </div>
                <span class="text-sm font-medium transition-colors"
                      :class="step >= 2 ? 'text-slate-800' : 'text-slate-400'">
                    Address & Owner
                </span>
            </div>

            {{-- Line 2→3 --}}
            <div class="flex-1 h-px mx-4 transition-all duration-500"
                 :class="step > 2 ? 'bg-blue-600' : 'bg-slate-200'"></div>

            {{-- Step 3 --}}
            <div class="flex items-center gap-2 flex-shrink-0">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                            border-2 transition-all duration-300"
                     :class="step > 3
                         ? 'bg-blue-600 border-blue-600 text-white'
                         : step === 3
                             ? 'bg-blue-600 border-blue-600 text-white'
                             : 'bg-white border-slate-300 text-slate-400'">
                    <span x-show="step > 3"><i class="bi bi-check-lg text-xs"></i></span>
                    <span x-show="step <= 3">3</span>
                </div>
                <span class="text-sm font-medium transition-colors"
                      :class="step >= 3 ? 'text-slate-800' : 'text-slate-400'">
                    Contact & Activity
                </span>
            </div>

            {{-- Line 3→4 --}}
            <div class="flex-1 h-px mx-4 transition-all duration-500"
                 :class="step > 3 ? 'bg-blue-600' : 'bg-slate-200'"></div>

            {{-- Step 4 --}}
            <div class="flex items-center gap-2 flex-shrink-0">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                            border-2 transition-all duration-300"
                     :class="step === 4
                         ? 'bg-blue-600 border-blue-600 text-white'
                         : 'bg-white border-slate-300 text-slate-400'">
                    4
                </div>
                <span class="text-sm font-medium transition-colors"
                      :class="step >= 4 ? 'text-slate-800' : 'text-slate-400'">
                    Documents
                </span>
            </div>

        </div>
    </div>

    {{-- ── FORM ── --}}
    <form action="{{ route('user.business.renew.store', [
                        'userId'      => auth()->id(),
                        'application' => $application->id
                   ]) }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf

        {{-- ══════════════════════════════════════ --}}
        {{-- STEP 1 — Business Information           --}}
        {{-- ══════════════════════════════════════ --}}
        <div x-show="step === 1"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-x-3"
             x-transition:enter-end="opacity-100 translate-x-0">

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">

                {{-- Section Header --}}
                <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100">
                    <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-building text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800">Business Information</h3>
                        <p class="text-xs text-slate-400">Basic details about your business</p>
                    </div>
                </div>

                <div class="p-6 space-y-6">

                    {{-- Billing Frequency + Business Type --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                        {{-- Billing Frequency --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-3">
                                Billing Frequency <span class="text-red-500">*</span>
                            </label>
                            <div class="space-y-2.5">
                                @foreach(['Annually', 'Bi-Annually', 'Quarterly'] as $freq)
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <input type="radio"
                                               name="billing_frequency"
                                               value="{{ $freq }}"
                                               x-model="billingFrequency"
                                               {{ old('billing_frequency', $application->billing_frequency ?? '') === $freq ? 'checked' : '' }}
                                               class="w-4 h-4 text-blue-600 border-slate-300 focus:ring-blue-500" />
                                        <span class="text-sm text-slate-700 group-hover:text-slate-900">
                                            {{ $freq }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            @error('billing_frequency')
                                <p class="text-xs text-red-500 mt-1 flex items-center gap-1">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Business Type --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-3">
                                Business Type & Registration <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-2.5">
                                @php
                                    $types = [
                                        'Sole Proprietorship',
                                        'One Person Corporation',
                                        'Partnerships',
                                        'Corporation',
                                        'Cooperation',
                                    ];
                                @endphp
                                @foreach($types as $type)
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <input type="radio"
                                               name="business_type"
                                               value="{{ $type }}"
                                               x-model="businessType"
                                               {{ old('business_type', $application->business_type) === $type ? 'checked' : '' }}
                                               class="w-4 h-4 text-blue-600 border-slate-300 focus:ring-blue-500" />
                                        <span class="text-sm text-slate-700 group-hover:text-slate-900">
                                            {{ $type }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            @error('business_type')
                                <p class="text-xs text-red-500 mt-1 flex items-center gap-1">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>

                    <div class="border-t border-slate-100"></div>

                    {{-- Business Name + Trade Name --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">
                                Business Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="business_name"
                                   value="{{ old('business_name', $application->business_name) }}"
                                   placeholder="Enter your business name"
                                   class="w-full px-3 py-2.5 border rounded-lg text-sm text-slate-700
                                          placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500
                                          {{ $errors->has('business_name') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}" />
                            @error('business_name')
                                <p class="text-xs text-red-500 mt-1 flex items-center gap-1">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">
                                Trade Name / Franchise Name
                            </label>
                            <input type="text"
                                   name="trade_name"
                                   value="{{ old('trade_name', $application->trade_name ?? '') }}"
                                   placeholder="Enter trade name (optional)"
                                   class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm
                                          text-slate-700 placeholder-slate-400 focus:outline-none
                                          focus:ring-2 focus:ring-blue-500" />
                        </div>
                    </div>

                    {{-- DTI + TIN --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">
                                DTI/SEC/CDA Registration No. <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="dti_registration_no"
                                   value="{{ old('dti_registration_no', $application->dti_registration_no ?? '') }}"
                                   placeholder="Enter registration number"
                                   class="w-full px-3 py-2.5 border rounded-lg text-sm text-slate-700
                                          placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500
                                          {{ $errors->has('dti_registration_no') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}" />
                            @error('dti_registration_no')
                                <p class="text-xs text-red-500 mt-1 flex items-center gap-1">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">
                                Tax Identification Number (TIN) <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="tin"
                                   value="{{ old('tin', $application->tin ?? '') }}"
                                   placeholder="Enter TIN"
                                   class="w-full px-3 py-2.5 border rounded-lg text-sm text-slate-700
                                          placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500
                                          {{ $errors->has('tin') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}" />
                            @error('tin')
                                <p class="text-xs text-red-500 mt-1 flex items-center gap-1">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Renewal Year + Date of Renewal --}}
                    <div class="border-t border-slate-100 pt-5">
                        <p class="text-sm font-medium text-slate-700 mb-3 flex items-center gap-2">
                            <i class="bi bi-calendar3 text-slate-400"></i>
                            Renewal Period
                        </p>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">
                                    Renewal Year <span class="text-red-500">*</span>
                                </label>
                                <select name="renewal_year"
                                        class="w-full px-3 py-2.5 border rounded-lg text-sm text-slate-700
                                               focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white
                                               {{ $errors->has('renewal_year') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}">
                                    @for($y = now()->year; $y <= now()->year + 1; $y++)
                                        <option value="{{ $y }}"
                                            {{ old('renewal_year', now()->year) == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                                @error('renewal_year')
                                    <p class="text-xs text-red-500 mt-1 flex items-center gap-1">
                                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">
                                    Date of Renewal Application <span class="text-red-500">*</span>
                                </label>
                                <input type="date"
                                       name="renewal_date"
                                       value="{{ old('renewal_date', now()->format('Y-m-d')) }}"
                                       min="{{ now()->format('Y-m-d') }}"
                                       class="w-full px-3 py-2.5 border rounded-lg text-sm text-slate-700
                                              focus:outline-none focus:ring-2 focus:ring-blue-500
                                              {{ $errors->has('renewal_date') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}" />
                                @error('renewal_date')
                                    <p class="text-xs text-red-500 mt-1 flex items-center gap-1">
                                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Footer --}}
                <div class="flex justify-end px-6 py-4 border-t border-slate-100 bg-slate-50">
                    <button type="button" @click="next()"
                            class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white
                                   text-sm font-semibold px-6 py-2.5 rounded-lg transition-colors shadow-sm">
                        Next Step <i class="bi bi-arrow-right"></i>
                    </button>
                </div>

            </div>
        </div>

        {{-- ══════════════════════════════════════ --}}
        {{-- STEP 2 — Address & Owner               --}}
        {{-- ══════════════════════════════════════ --}}
        <div x-show="step === 2"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-x-3"
             x-transition:enter-end="opacity-100 translate-x-0">

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">

                {{-- Section Header --}}
                <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100">
                    <div class="w-9 h-9 rounded-lg bg-green-50 flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-geo-alt text-green-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800">Business Address & Owner</h3>
                        <p class="text-xs text-slate-400">Location and ownership details</p>
                    </div>
                </div>

                <div class="p-6 space-y-5">

                    {{-- Business Address Label --}}
                    <p class="text-sm font-medium text-slate-700 flex items-center gap-2">
                        <i class="bi bi-person text-slate-400"></i>
                        Business Address <span class="text-red-500">*</span>
                    </p>

                    {{-- Row 1: House/Bldg No. | Building Name | Lot No. --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">
                                House / Bldg No.
                            </label>
                            <input type="text"
                                   name="house_bldg_no"
                                   value="{{ old('house_bldg_no', $application->house_bldg_no ?? '') }}"
                                   placeholder="House / Bldg No."
                                   class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm
                                          text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">
                                Building Name
                            </label>
                            <input type="text"
                                   name="building_name"
                                   value="{{ old('building_name', $application->building_name ?? '') }}"
                                   placeholder="Building Name"
                                   class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm
                                          text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">
                                Lot No.
                            </label>
                            <input type="text"
                                   name="lot_no"
                                   value="{{ old('lot_no', $application->lot_no ?? '') }}"
                                   placeholder="Lot No."
                                   class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm
                                          text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                    </div>

                    {{-- Row 2: Block No. | Street | Barangay --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">
                                Block No.
                            </label>
                            <input type="text"
                                   name="block_no"
                                   value="{{ old('block_no', $application->block_no ?? '') }}"
                                   placeholder="Block No."
                                   class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm
                                          text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">
                                Street
                            </label>
                            <input type="text"
                                   name="street"
                                   value="{{ old('street', $application->street ?? '') }}"
                                   placeholder="Street"
                                   class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm
                                          text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">
                                Barangay <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="barangay"
                                   value="{{ old('barangay', $application->barangay ?? '') }}"
                                   placeholder="Barangay"
                                   class="w-full px-3 py-2.5 border rounded-lg text-sm text-slate-700
                                          placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500
                                          {{ $errors->has('barangay') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}" />
                            @error('barangay')
                                <p class="text-xs text-red-500 mt-1 flex items-center gap-1">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Row 3: Subdivision | City/Municipality | Province --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">
                                Subdivision
                            </label>
                            <input type="text"
                                   name="subdivision"
                                   value="{{ old('subdivision', $application->subdivision ?? '') }}"
                                   placeholder="Subdivision"
                                   class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm
                                          text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">
                                City / Municipality <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="city"
                                   value="{{ old('city', $application->city ?? '') }}"
                                   placeholder="City / Municipality"
                                   class="w-full px-3 py-2.5 border rounded-lg text-sm text-slate-700
                                          placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500
                                          {{ $errors->has('city') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}" />
                            @error('city')
                                <p class="text-xs text-red-500 mt-1 flex items-center gap-1">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">
                                Province <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="province"
                                   value="{{ old('province', $application->province ?? '') }}"
                                   placeholder="Province"
                                   class="w-full px-3 py-2.5 border rounded-lg text-sm text-slate-700
                                          placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500
                                          {{ $errors->has('province') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}" />
                            @error('province')
                                <p class="text-xs text-red-500 mt-1 flex items-center gap-1">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Row 4: Zip Code --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">
                                Zip Code <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="zip_code"
                                   value="{{ old('zip_code', $application->zip_code ?? '') }}"
                                   placeholder="Zip Code"
                                   class="w-full px-3 py-2.5 border rounded-lg text-sm text-slate-700
                                          placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500
                                          {{ $errors->has('zip_code') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}" />
                            @error('zip_code')
                                <p class="text-xs text-red-500 mt-1 flex items-center gap-1">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Owner Fields — shown based on business type --}}
                    <div class="border-t border-slate-100 pt-5">

                        {{-- Notice if no type selected --}}
                        <div x-show="businessType === ''"
                             class="flex items-center gap-2 bg-yellow-50 border border-yellow-200
                                    rounded-lg px-4 py-3 text-sm text-yellow-700">
                            <i class="bi bi-info-circle flex-shrink-0"></i>
                            Please select a business type in Step 1 to see owner fields.
                        </div>

                        {{-- Owner fields shown when type is selected --}}
                        <div x-show="businessType !== ''" class="space-y-4">
                            <p class="text-sm font-medium text-slate-700 flex items-center gap-2">
                                <i class="bi bi-person text-slate-400"></i>
                                Owner / Representative Information <span class="text-red-500">*</span>
                            </p>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">
                                        Owner / Representative Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                           name="owner_name"
                                           value="{{ old('owner_name', $application->owner_name ?? '') }}"
                                           placeholder="Full name"
                                           class="w-full px-3 py-2.5 border rounded-lg text-sm text-slate-700
                                                  placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500
                                                  {{ $errors->has('owner_name') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}" />
                                    @error('owner_name')
                                        <p class="text-xs text-red-500 mt-1 flex items-center gap-1">
                                            <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">
                                        Position / Designation
                                    </label>
                                    <input type="text"
                                           name="owner_position"
                                           value="{{ old('owner_position', $application->owner_position ?? '') }}"
                                           placeholder="e.g. Owner, President, Manager"
                                           class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm
                                                  text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 bg-slate-50">
                    <button type="button" @click="prev()"
                            class="flex items-center gap-2 bg-white border border-slate-300 hover:bg-slate-50
                                   text-slate-700 text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                        <i class="bi bi-arrow-left"></i> Previous
                    </button>
                    <button type="button" @click="next()"
                            class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white
                                   text-sm font-semibold px-6 py-2.5 rounded-lg transition-colors shadow-sm">
                        Next Step <i class="bi bi-arrow-right"></i>
                    </button>
                </div>

            </div>
        </div>

        {{-- ══════════════════════════════════════ --}}
        {{-- STEP 3 — Contact & Activity            --}}
        {{-- ══════════════════════════════════════ --}}
        <div x-show="step === 3"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-x-3"
             x-transition:enter-end="opacity-100 translate-x-0">

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">

                {{-- Section Header --}}
                <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100">
                    <div class="w-9 h-9 rounded-lg bg-purple-50 flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-telephone text-purple-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800">Contact & Business Activity</h3>
                        <p class="text-xs text-slate-400">How to reach your business and what it does</p>
                    </div>
                </div>

                <div class="p-6 space-y-6">

                    {{-- Business Contact Info --}}
                    <div>
                        <p class="text-sm font-medium text-slate-700 mb-3 flex items-center gap-2">
                            <i class="bi bi-telephone text-slate-400"></i>
                            Business Contact Information <span class="text-red-500">*</span>
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                            {{-- Telephone --}}
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">
                                    Telephone Number
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs">
                                        <i class="bi bi-telephone"></i>
                                    </span>
                                    <input type="text"
                                           name="telephone_number"
                                           value="{{ old('telephone_number', $application->telephone_number ?? '') }}"
                                           placeholder="(02) 1234-5678"
                                           class="w-full pl-8 pr-3 py-2.5 border border-slate-300 rounded-lg text-sm
                                                  text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                </div>
                            </div>

                            {{-- Phone --}}
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">
                                    Phone Number <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs">
                                        <i class="bi bi-phone"></i>
                                    </span>
                                    <input type="text"
                                           name="owner_contact"
                                           value="{{ old('owner_contact', $application->owner_contact ?? '') }}"
                                           placeholder="09XX-XXX-XXXX"
                                           class="w-full pl-8 pr-3 py-2.5 border rounded-lg text-sm text-slate-700
                                                  placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500
                                                  {{ $errors->has('owner_contact') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}" />
                                </div>
                                @error('owner_contact')
                                    <p class="text-xs text-red-500 mt-1 flex items-center gap-1">
                                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input type="email"
                                           name="owner_email"
                                           value="{{ old('owner_email', $application->owner_email ?? '') }}"
                                           placeholder="business@example.com"
                                           class="w-full pl-8 pr-3 py-2.5 border rounded-lg text-sm text-slate-700
                                                  placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500
                                                  {{ $errors->has('owner_email') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}" />
                                </div>
                                @error('owner_email')
                                    <p class="text-xs text-red-500 mt-1 flex items-center gap-1">
                                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <div class="border-t border-slate-100"></div>

                    {{-- Business Activity --}}
                    <div>
                        <p class="text-sm font-medium text-slate-700 mb-3 flex items-center gap-2">
                            <i class="bi bi-briefcase text-slate-400"></i>
                            Business Activity <span class="text-red-500">*</span>
                        </p>
                        @php
                            $activities = [
                                'Main Office'      => ['icon' => 'bi-building',       'desc' => 'Primary business location'],
                                'Branch Office'    => ['icon' => 'bi-diagram-3',      'desc' => 'Additional branch'],
                                'Admin Office Only'=> ['icon' => 'bi-clipboard',      'desc' => 'Administrative operations'],
                                'Warehouse'        => ['icon' => 'bi-box-seam',       'desc' => 'Storage facility'],
                            ];
                        @endphp
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            @foreach($activities as $activity => $config)
                                <label class="flex items-start gap-3 p-3.5 border rounded-xl cursor-pointer
                                              transition-all duration-200 group"
                                       :class="businessActivity === '{{ $activity }}'
                                           ? 'border-blue-500 bg-blue-50'
                                           : 'border-slate-200 hover:border-slate-300 bg-white'">
                                    <input type="radio"
                                           name="business_activity"
                                           value="{{ $activity }}"
                                           x-model="businessActivity"
                                           {{ old('business_activity', $application->business_activity ?? '') === $activity ? 'checked' : '' }}
                                           class="mt-0.5 w-4 h-4 text-blue-600 border-slate-300 focus:ring-blue-500 flex-shrink-0" />
                                    <div>
                                        <p class="text-sm font-medium text-slate-800 flex items-center gap-1.5">
                                            <i class="bi {{ $config['icon'] }} text-slate-500 text-xs"></i>
                                            {{ $activity }}
                                        </p>
                                        <p class="text-xs text-slate-400 mt-0.5">{{ $config['desc'] }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('business_activity')
                            <p class="text-xs text-red-500 mt-1 flex items-center gap-1">
                                <i class="bi bi-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    

                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 bg-slate-50">
                    <button type="button" @click="prev()"
                            class="flex items-center gap-2 bg-white border border-slate-300 hover:bg-slate-50
                                   text-slate-700 text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                        <i class="bi bi-arrow-left"></i> Previous
                    </button>
                    <button type="button" @click="next()"
                            class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white
                                   text-sm font-semibold px-6 py-2.5 rounded-lg transition-colors shadow-sm">
                        Next Step <i class="bi bi-arrow-right"></i>
                    </button>
                </div>

            </div>
        </div>

        {{-- ══════════════════════════════════════ --}}
        {{-- STEP 4 — Documents                     --}}
        {{-- ══════════════════════════════════════ --}}
        <div x-show="step === 4"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-x-3"
             x-transition:enter-end="opacity-100 translate-x-0">

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">

                {{-- Section Header --}}
                <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100">
                    <div class="w-9 h-9 rounded-lg bg-orange-50 flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-cloud-upload text-orange-500"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800">Upload Documents</h3>
                        <p class="text-xs text-slate-400">Attach the required documents for your renewal</p>
                    </div>
                </div>

                <div class="p-6 space-y-5">

                    {{-- Info Banner --}}
                    <div class="flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-lg px-4 py-3">
                        <i class="bi bi-info-circle text-blue-500 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-semibold text-blue-800">All 4 documents are required</p>
                            <p class="text-xs text-blue-600 mt-0.5">
                                Accepted formats: JPEG, PNG, PDF, GIF — Max 2MB each
                            </p>
                        </div>
                    </div>

                    {{-- Document Uploads --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

                        @php
                            $documents = [
                                'dti_certificate'    => ['label' => 'DTI/SEC Certificate',        'icon' => 'bi-file-earmark-text'],
                                'valid_id'           => ['label' => 'Valid ID with 3 Signatures', 'icon' => 'bi-person-badge'],
                                'photo_of_business'  => ['label' => 'Photo of Business',          'icon' => 'bi-camera'],
                                'location_sketch'    => ['label' => 'Business Location Sketch',   'icon' => 'bi-grid-1x2'],
                            ];
                        @endphp

                        @foreach($documents as $field => $doc)
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2 flex items-center gap-1.5">
                                    <i class="bi {{ $doc['icon'] }} text-slate-400"></i>
                                    {{ $doc['label'] }} <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center gap-0 border border-slate-300 rounded-lg overflow-hidden
                                            {{ $errors->has($field) ? 'border-red-400' : '' }}">
                                    <label class="flex-shrink-0 px-4 py-2.5 bg-slate-100 hover:bg-slate-200
                                                  text-slate-700 text-sm font-medium cursor-pointer border-r
                                                  border-slate-300 transition-colors">
                                        Choose File
                                        <input type="file"
                                               name="{{ $field }}"
                                               accept=".jpg,.jpeg,.png,.pdf,.gif"
                                               class="hidden"
                                               onchange="updateFileName(this)" />
                                    </label>
                                    <span class="px-3 text-sm text-slate-400 truncate file-label">
                                        No file chosen
                                    </span>
                                </div>
                                @error($field)
                                    <p class="text-xs text-red-500 mt-1 flex items-center gap-1">
                                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        @endforeach

                    </div>

                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 bg-slate-50">
                    <button type="button" @click="prev()"
                            class="flex items-center gap-2 bg-white border border-slate-300 hover:bg-slate-50
                                   text-slate-700 text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                        <i class="bi bi-arrow-left"></i> Previous
                    </button>
                    <button type="submit"
                            class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white
                                   text-sm font-semibold px-6 py-2.5 rounded-lg transition-colors shadow-sm">
                        <i class="bi bi-send-fill"></i> Submit Application
                    </button>
                </div>

            </div>
        </div>

    </form>

</div>

<script>
function updateFileName(input) {
    const label = input.closest('label').nextElementSibling
        ?? input.closest('.flex').querySelector('.file-label');
    if (input.files && input.files.length > 0) {
        // Find the sibling span with class file-label
        const span = input.closest('.flex').querySelector('.file-label');
        if (span) span.textContent = input.files[0].name;
    }
}

// Fix for payment method radio cards
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function () {
            document.querySelectorAll('[name="payment_method"]').forEach(r => {
                const card = r.closest('label');
                if (r.checked) {
                    card.classList.add('border-blue-500', 'bg-blue-50');
                    card.classList.remove('border-slate-200');
                } else {
                    card.classList.remove('border-blue-500', 'bg-blue-50');
                    card.classList.add('border-slate-200');
                }
            });
        });
    });
});
</script>

@endsection