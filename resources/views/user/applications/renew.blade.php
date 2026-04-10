@extends('layouts.app')

@section('title', 'Renew Business Permit')

@section('content')
<div class="max-w-5xl mx-auto">
    {{-- Header --}}
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('user.business.show', ['userId' => Auth::id(), 'application' => $application->id]) }}"
               class="inline-flex items-center justify-center w-10 h-10 rounded-lg border border-slate-300 bg-white text-slate-600 hover:bg-slate-50 transition">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Renew Business Permit</h1>
                <p class="text-sm text-slate-500">
                    Application: <span class="font-medium text-slate-700">{{ $application->application_number }}</span>
                </p>
            </div>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3">
            <p class="text-sm font-semibold text-red-800 mb-2">Please fix the following:</p>
            <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Current Permit Summary --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
        <h2 class="text-sm font-semibold text-slate-800 mb-4 flex items-center gap-2">
            <i class="bi bi-info-circle text-slate-400"></i>
            Current Permit Information
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-slate-50 rounded-xl p-4">
                <p class="text-xs uppercase tracking-wide text-slate-400 font-semibold">Business Name</p>
                <p class="text-sm font-medium text-slate-800 mt-1">{{ $application->business_name }}</p>
            </div>

            <div class="bg-slate-50 rounded-xl p-4">
                <p class="text-xs uppercase tracking-wide text-slate-400 font-semibold">Application Number</p>
                <p class="text-sm font-medium text-slate-800 mt-1">{{ $application->application_number }}</p>
            </div>

            <div class="bg-slate-50 rounded-xl p-4">
                <p class="text-xs uppercase tracking-wide text-slate-400 font-semibold">Current Billing Frequency</p>
                <p class="text-sm font-medium text-slate-800 mt-1">{{ $application->billing_freq ?? '—' }}</p>
            </div>

            <div class="bg-slate-50 rounded-xl p-4">
                <p class="text-xs uppercase tracking-wide text-slate-400 font-semibold">Valid Until</p>
                <p class="text-sm font-medium text-slate-800 mt-1">
                    {{ $application->permit_valid_until ? \Carbon\Carbon::parse($application->permit_valid_until)->format('F d, Y') : '—' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Renewal Form --}}
    <form method="POST"
          action="{{ route('user.business.renew.store', ['userId' => Auth::id(), 'application' => $application->id]) }}"
          enctype="multipart/form-data">
        @csrf

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            {{-- Section Header --}}
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h2 class="text-sm font-semibold text-slate-800 flex items-center gap-2">
                    <i class="bi bi-arrow-repeat text-slate-400"></i>
                    Renewal Details
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Review your existing information and choose a new billing frequency for renewal.
                </p>
            </div>

            <div class="p-6 space-y-6">
                {{-- Business Info --}}
                <div>
                    <h3 class="text-sm font-semibold text-slate-700 mb-3">Business Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Business Name</label>
                            <input type="text" name="business_name" value="{{ old('business_name', $application->business_name) }}"
                                   class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Trade Name</label>
                            <input type="text" name="trade_name" value="{{ old('trade_name', $application->trade_name) }}"
                                   class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Registration No.</label>
                            <input type="text" name="reg_num" value="{{ old('reg_num', $application->reg_num) }}"
                                   class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Business TIN</label>
                            <input type="text" name="business_tin" value="{{ old('business_tin', $application->business_tin) }}"
                                   class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                {{-- Contact Info --}}
                <div>
                    <h3 class="text-sm font-semibold text-slate-700 mb-3">Contact Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Telephone No.</label>
                            <input type="text" name="telephone_num" value="{{ old('telephone_num', $application->telephone_num) }}"
                                   class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Phone Number</label>
                            <input type="text" name="phone_number" value="{{ old('phone_number', $application->phone_number) }}"
                                   class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Business Email</label>
                            <input type="email" name="business_email" value="{{ old('business_email', $application->business_email) }}"
                                   class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500" required>
                        </div>
                    </div>
                </div>

                {{-- Billing Frequency --}}
                <div>
                    <h3 class="text-sm font-semibold text-slate-700 mb-3">Renewal Billing Frequency</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        @php
                            $selectedBilling = old('billing_freq', $application->billing_freq);
                            $billingOptions = [
                                'monthly' => 'Monthly',
                                'quarterly' => 'Quarterly',
                                'bi-annually' => 'Bi-Annually',
                                'annually' => 'Annually',
                            ];
                        @endphp

                        @foreach($billingOptions as $value => $label)
                            <label class="cursor-pointer">
                                <input type="radio" name="billing_freq" value="{{ $value }}"
                                       class="peer sr-only"
                                       {{ $selectedBilling === $value ? 'checked' : '' }}
                                       required>
                                <div class="rounded-xl border border-slate-300 bg-white px-4 py-4 text-sm font-medium text-slate-700 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-700 hover:border-slate-400 transition">
                                    <div class="flex items-center justify-between">
                                        <span>{{ $label }}</span>
                                        <i class="bi bi-check-circle opacity-0 peer-checked:opacity-100"></i>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <p class="text-xs text-slate-400 mt-2">
                        Your renewed permit validity will follow the selected billing frequency.
                    </p>
                </div>

                {{-- Address --}}
                <div>
                    <h3 class="text-sm font-semibold text-slate-700 mb-3">Business Address</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">House/Bldg No.</label>
                            <input type="text" name="house_num" value="{{ old('house_num', $application->house_num) }}"
                                   class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Building Name</label>
                            <input type="text" name="building_name" value="{{ old('building_name', $application->building_name) }}"
                                   class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Street</label>
                            <input type="text" name="street" value="{{ old('street', $application->street) }}"
                                   class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Barangay</label>
                            <input type="text" name="barangay" value="{{ old('barangay', $application->barangay) }}"
                                   class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Subdivision</label>
                            <input type="text" name="subdivision" value="{{ old('subdivision', $application->subdivision) }}"
                                   class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">City / Municipality</label>
                            <input type="text" name="city_muni" value="{{ old('city_muni', $application->city_muni) }}"
                                   class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Province</label>
                            <input type="text" name="province" value="{{ old('province', $application->province) }}"
                                   class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Zip Code</label>
                            <input type="text" name="zip_code" value="{{ old('zip_code', $application->zip_code) }}"
                                   class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                {{-- Documents --}}
                <div>
                    <h3 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                        <i class="bi bi-file-earmark-arrow-up text-slate-400"></i>
                        Renewal Documents
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- DTI / SEC Certificate --}}
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                DTI / SEC Certificate
                            </label>
                            <input type="file"
                                   name="dti_sec_certificate"
                                   accept=".jpg,.jpeg,.png,.pdf"
                                   class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-blue-700">
                            <p class="text-xs text-slate-400 mt-2">Accepted: JPG, PNG, PDF</p>
                        </div>

                        {{-- Valid ID --}}
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Valid ID
                            </label>
                            <input type="file"
                                   name="valid_id"
                                   accept=".jpg,.jpeg,.png,.pdf"
                                   class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-green-600 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-green-700">
                            <p class="text-xs text-slate-400 mt-2">Accepted: JPG, PNG, PDF</p>
                        </div>

                        {{-- Business Photo --}}
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Business Photo
                            </label>
                            <input type="file"
                                   name="business_photo"
                                   accept=".jpg,.jpeg,.png,.pdf"
                                   class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-purple-600 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-purple-700">
                            <p class="text-xs text-slate-400 mt-2">Accepted: JPG, PNG, PDF</p>
                        </div>

                        {{-- Business Sketch --}}
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Business Sketch
                            </label>
                            <input type="file"
                                   name="business_sketch"
                                   accept=".jpg,.jpeg,.png,.pdf"
                                   class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-orange-600 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-orange-700">
                            <p class="text-xs text-slate-400 mt-2">Accepted: JPG, PNG, PDF</p>
                        </div>
                    </div>

                    <div class="mt-3 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3">
                        <p class="text-xs text-blue-700">
                            Upload updated files only if needed. If your renewal process requires all documents again,
                            you can make these required in validation.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Footer Actions --}}
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-xs text-slate-500">
                    Submitting this form will create your renewal request for review.
                </p>

                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('user.business.show', ['userId' => Auth::id(), 'application' => $application->id]) }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                        <i class="bi bi-x-circle"></i>
                        Cancel
                    </a>

                    <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition shadow-sm">
                        <i class="bi bi-arrow-repeat"></i>
                        Submit Renewal
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection