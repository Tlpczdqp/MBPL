@extends('layouts.app')

@section('title', 'Apply for Business Permit')

@section('content')
<div x-data="{ 
    businessInfo: '{{ old('business_info') }}',
    currentStep: 1,
    files: [],
    addFiles(inputName, e) {
        const input = e.target || e.dataTransfer;
        Array.from(input.files).forEach(f => {
            this.files.push({ 
                id: Date.now() + Math.random(), 
                name: f.name, 
                size: (f.size / 1024).toFixed(0) + ' KB',
                field: inputName
            });
        });
    },
    removeFile(id) {
        this.files = this.files.filter(f => f.id !== id);
    }
}">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Apply for Business Permit</h1>
            <p class="text-sm text-slate-500 mt-1">Fill in the details below to submit your business permit application.</p>
        </div>
        <a href="{{ route('user.business.index', ['userId' => Auth::id()]) }}"
           class="mt-3 sm:mt-0 inline-flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-slate-800 transition">
            <i class="bi bi-arrow-left"></i>
            Back to Applications
        </a>
    </div>

    {{-- Progress Steps --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 cursor-pointer" @click="currentStep = 1">
                <div class="flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold transition"
                     :class="currentStep >= 1 ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-500'">
                    <span x-show="currentStep <= 1">1</span>
                    <i x-show="currentStep > 1" class="bi bi-check-lg" x-cloak></i>
                </div>
                <span class="text-sm font-medium hidden sm:inline"
                      :class="currentStep >= 1 ? 'text-slate-800' : 'text-slate-400'">Business Info</span>
            </div>
            <div class="flex-1 h-0.5 mx-3 rounded" :class="currentStep > 1 ? 'bg-blue-600' : 'bg-slate-200'"></div>

            <div class="flex items-center gap-2 cursor-pointer" @click="if(currentStep > 1) currentStep = 2">
                <div class="flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold transition"
                     :class="currentStep >= 2 ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-500'">
                    <span x-show="currentStep <= 2">2</span>
                    <i x-show="currentStep > 2" class="bi bi-check-lg" x-cloak></i>
                </div>
                <span class="text-sm font-medium hidden sm:inline"
                      :class="currentStep >= 2 ? 'text-slate-800' : 'text-slate-400'">Address & Owner</span>
            </div>
            <div class="flex-1 h-0.5 mx-3 rounded" :class="currentStep > 2 ? 'bg-blue-600' : 'bg-slate-200'"></div>

            <div class="flex items-center gap-2 cursor-pointer" @click="if(currentStep > 2) currentStep = 3">
                <div class="flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold transition"
                     :class="currentStep >= 3 ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-500'">
                    <span x-show="currentStep <= 3">3</span>
                    <i x-show="currentStep > 3" class="bi bi-check-lg" x-cloak></i>
                </div>
                <span class="text-sm font-medium hidden sm:inline"
                      :class="currentStep >= 3 ? 'text-slate-800' : 'text-slate-400'">Contact & Activity</span>
            </div>
            <div class="flex-1 h-0.5 mx-3 rounded" :class="currentStep > 3 ? 'bg-blue-600' : 'bg-slate-200'"></div>

            <div class="flex items-center gap-2 cursor-pointer" @click="if(currentStep > 3) currentStep = 4">
                <div class="flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold transition"
                     :class="currentStep >= 4 ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-500'">4</div>
                <span class="text-sm font-medium hidden sm:inline"
                      :class="currentStep >= 4 ? 'text-slate-800' : 'text-slate-400'">Documents</span>
            </div>
        </div>
    </div>

    {{-- ✅ FIXED: route name is user.business.store --}}
    <form action="{{ route('user.business.store', ['userId' => Auth::id()]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="transact_type" value="New" />

        {{-- ============================================================== --}}
        {{-- STEP 1: Business Information --}}
        {{-- ============================================================== --}}
        <div x-show="currentStep === 1" x-cloak>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 border-b border-slate-200 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-100">
                            <i class="bi bi-building text-blue-600 text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-slate-800">Business Information</h2>
                            <p class="text-sm text-slate-500">Basic details about your business</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                        {{-- Billing Frequency --}}
                        <div class="space-y-3">
                            <label class="text-sm font-semibold text-slate-700">
                                Billing Frequency <span class="text-red-500">*</span>
                            </label>
                            <div class="bg-slate-50 rounded-lg p-4 space-y-3">
                                @foreach(['Annually', 'Bi-Annually', 'Quarterly'] as $freq)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="radio" name="billing_freq" value="{{ $freq }}"
                                           {{ old('billing_freq') == $freq ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 border-slate-300 focus:ring-blue-500" />
                                    <span class="text-sm text-slate-700 group-hover:text-slate-900">{{ $freq }}</span>
                                </label>
                                @endforeach
                            </div>
                            @error('billing_freq')
                                <p class="text-xs text-red-600 flex items-center gap-1">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Business Type --}}
                        <div class="space-y-3">
                            <label class="text-sm font-semibold text-slate-700">
                                Business Type & Registration <span class="text-red-500">*</span>
                            </label>
                            <div class="bg-slate-50 rounded-lg p-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach([
                                    'Sole Proprietorship',
                                    'Corporation',
                                    'One Person Corporation',
                                    'Cooperation',
                                    'Partnerships'
                                ] as $type)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="radio" name="business_info" value="{{ $type }}"
                                           x-model="businessInfo"
                                           {{ old('business_info') == $type ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 border-slate-300 focus:ring-blue-500" />
                                    <span class="text-sm text-slate-700 group-hover:text-slate-900">{{ $type }}</span>
                                </label>
                                @endforeach
                            </div>
                            @error('business_info')
                                <p class="text-xs text-red-600 flex items-center gap-1">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Business Name & Trade Name --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="business_name" class="text-sm font-semibold text-slate-700">
                                Business Name <span class="text-red-500">*</span>
                            </label>
                            <input id="business_name" type="text" name="business_name" value="{{ old('business_name') }}"
                                   placeholder="Enter your business name"
                                   class="h-10 w-full rounded-lg border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm transition 
                                          placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none
                                          {{ $errors->has('business_name') ? 'border-red-400' : '' }}" />
                            @error('business_name')
                                <p class="text-xs text-red-600 flex items-center gap-1">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="trade_name" class="text-sm font-semibold text-slate-700">
                                Trade Name / Franchise Name
                            </label>
                            <input id="trade_name" type="text" name="trade_name" value="{{ old('trade_name') }}"
                                   placeholder="Enter trade name (optional)"
                                   class="h-10 w-full rounded-lg border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm transition 
                                          placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none" />
                        </div>
                    </div>

                    {{-- Registration Number & TIN --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="reg_num" class="text-sm font-semibold text-slate-700">
                                DTI/SEC/CDA Registration No. <span class="text-red-500">*</span>
                            </label>
                            <input id="reg_num" type="text" name="reg_num" value="{{ old('reg_num') }}"
                                   placeholder="Enter registration number"
                                   class="h-10 w-full rounded-lg border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm transition 
                                          placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none
                                          {{ $errors->has('reg_num') ? 'border-red-400' : '' }}" />
                            @error('reg_num')
                                <p class="text-xs text-red-600 flex items-center gap-1">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="business_tin" class="text-sm font-semibold text-slate-700">
                                Tax Identification Number (TIN) <span class="text-red-500">*</span>
                            </label>
                            <input id="business_tin" type="text" name="business_tin" value="{{ old('business_tin') }}"
                                   placeholder="Enter TIN"
                                   class="h-10 w-full rounded-lg border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm transition 
                                          placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none
                                          {{ $errors->has('business_tin') ? 'border-red-400' : '' }}" />
                            @error('business_tin')
                                <p class="text-xs text-red-600 flex items-center gap-1">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 border-t border-slate-200 px-6 py-4 flex justify-end">
                    <button type="button" @click="currentStep = 2"
                            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700">
                        Next Step <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- ============================================================== --}}
        {{-- STEP 2: Address & Owner Information --}}
        {{-- ============================================================== --}}
        <div x-show="currentStep === 2" x-cloak>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 border-b border-slate-200 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-green-100">
                            <i class="bi bi-geo-alt text-green-600 text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-slate-800">Business Address & Owner</h2>
                            <p class="text-sm text-slate-500">Location and ownership details</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-700 mb-3">
                            <i class="bi bi-pin-map text-slate-400 mr-1"></i>
                            Business Address <span class="text-red-500">*</span>
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach([
                                ['house_num', 'House / Bldg No.'],
                                ['building_name', 'Building Name'],
                                ['lot_num', 'Lot No.'],
                                ['block_num', 'Block No.'],
                                ['street', 'Street'],
                                ['barangay', 'Barangay *'],
                                ['subdivision', 'Subdivision'],
                                ['city_muni', 'City / Municipality *'],
                                ['province', 'Province *'],
                                ['zip_code', 'Zip Code *'],
                            ] as [$name, $label])
                            <div class="space-y-1.5">
                                <label class="text-xs font-medium text-slate-500 uppercase tracking-wide">{{ $label }}</label>
                                <input type="text" name="{{ $name }}" value="{{ old($name) }}"
                                       placeholder="{{ str_replace(' *', '', $label) }}"
                                       class="h-10 w-full rounded-lg border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm transition 
                                              placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none
                                              {{ $errors->has($name) ? 'border-red-400' : '' }}" />
                                @error($name)
                                    <p class="text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="border-t border-slate-200"></div>

                    {{-- Sole Proprietorship Owner --}}
                    <div x-show="businessInfo === 'Sole Proprietorship'" x-cloak>
                        <h3 class="text-sm font-semibold text-slate-700 mb-3">
                            <i class="bi bi-person text-slate-400 mr-1"></i>
                            Owner Information (Sole Proprietorship)
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            @foreach([
                                ['sp_owner_lname', 'Surname'],
                                ['sp_owner_fname', 'Given Name'],
                                ['sp_owner_mname', 'Middle Name'],
                            ] as [$name, $label])
                            <div class="space-y-1.5">
                                <label class="text-xs font-medium text-slate-500 uppercase tracking-wide">{{ $label }}</label>
                                <input type="text" name="{{ $name }}" value="{{ old($name) }}" placeholder="{{ $label }}"
                                       class="h-10 w-full rounded-lg border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none" />
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Corporation / Cooperative / Partnerships --}}
                    <div x-show="businessInfo === 'Corporation' || businessInfo === 'One Person Corporation' || businessInfo === 'Partnerships' || businessInfo === 'Cooperation'" x-cloak>
                        <h3 class="text-sm font-semibold text-slate-700 mb-3">
                            <i class="bi bi-people text-slate-400 mr-1"></i>
                            President / Officer in Charge
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                            @foreach([
                                ['corp_owner_lname', 'Surname'],
                                ['corp_owner_fname', 'Given Name'],
                                ['corp_owner_mname', 'Middle Name'],
                            ] as [$name, $label])
                            <div class="space-y-1.5">
                                <label class="text-xs font-medium text-slate-500 uppercase tracking-wide">{{ $label }}</label>
                                <input type="text" name="{{ $name }}" value="{{ old($name) }}" placeholder="{{ $label }}"
                                       class="h-10 w-full rounded-lg border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none" />
                            </div>
                            @endforeach
                        </div>

                        <div class="space-y-3">
                            <label class="text-sm font-semibold text-slate-700">Corporation Type</label>
                            <div class="flex items-center gap-6">
                                @foreach(['Local', 'Foreign'] as $loc)
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="corp_location" value="{{ $loc }}"
                                           {{ old('corp_location') == $loc ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 border-slate-300 focus:ring-blue-500" />
                                    <span class="text-sm text-slate-700">{{ $loc }}</span>
                                </label>
                                @endforeach
                            </div>
                            @error('corp_location')
                                <p class="text-xs text-red-600"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div x-show="!businessInfo" x-cloak>
                        <div class="flex items-center gap-3 rounded-lg bg-amber-50 border border-amber-200 p-4">
                            <i class="bi bi-info-circle text-amber-500"></i>
                            <p class="text-sm text-amber-700">Please select a business type in Step 1 to see owner fields.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 border-t border-slate-200 px-6 py-4 flex justify-between">
                    <button type="button" @click="currentStep = 1"
                            class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-6 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                        <i class="bi bi-arrow-left"></i> Previous
                    </button>
                    <button type="button" @click="currentStep = 3"
                            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700">
                        Next Step <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- ============================================================== --}}
        {{-- STEP 3: Contact & Business Activity --}}
        {{-- ============================================================== --}}
        <div x-show="currentStep === 3" x-cloak>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 border-b border-slate-200 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-purple-100">
                            <i class="bi bi-telephone text-purple-600 text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-slate-800">Contact & Business Activity</h2>
                            <p class="text-sm text-slate-500">How to reach your business and what it does</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-700 mb-3">
                            <i class="bi bi-telephone text-slate-400 mr-1"></i>
                            Business Contact Information <span class="text-red-500">*</span>
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-xs font-medium text-slate-500 uppercase tracking-wide">Telephone Number</label>
                                <div class="relative">
                                    <i class="bi bi-telephone absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                    <input type="tel" name="telephone_num" value="{{ old('telephone_num') }}"
                                           placeholder="(02) 1234-5678"
                                           class="h-10 w-full rounded-lg border border-slate-300 bg-white pl-9 pr-4 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none" />
                                </div>
                                @error('telephone_num')
                                    <p class="text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-medium text-slate-500 uppercase tracking-wide">Phone Number <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <i class="bi bi-phone absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                    <input type="tel" name="phone_number" value="{{ old('phone_number') }}"
                                           placeholder="09XX-XXX-XXXX"
                                           class="h-10 w-full rounded-lg border border-slate-300 bg-white pl-9 pr-4 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none
                                                  {{ $errors->has('phone_number') ? 'border-red-400' : '' }}" />
                                </div>
                                @error('phone_number')
                                    <p class="text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-medium text-slate-500 uppercase tracking-wide">Email Address <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <i class="bi bi-envelope absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                    <input type="email" name="business_email" value="{{ old('business_email') }}"
                                           placeholder="business@example.com"
                                           class="h-10 w-full rounded-lg border border-slate-300 bg-white pl-9 pr-4 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none
                                                  {{ $errors->has('business_email') ? 'border-red-400' : '' }}" />
                                </div>
                                @error('business_email')
                                    <p class="text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-slate-200"></div>

                    <div>
                        <h3 class="text-sm font-semibold text-slate-700 mb-3">
                            <i class="bi bi-briefcase text-slate-400 mr-1"></i>
                            Business Activity <span class="text-red-500">*</span>
                        </h3>
                        <div class="bg-slate-50 rounded-lg p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach([
                                ['Main Office', 'bi-building', 'Primary business location'],
                                ['Branch Office', 'bi-building-add', 'Additional branch'],
                                ['Admin Office Only', 'bi-clipboard', 'Administrative operations'],
                                ['Warehouse', 'bi-box-seam', 'Storage facility'],
                            ] as [$value, $icon, $desc])
                            <label class="flex items-start gap-3 cursor-pointer p-3 rounded-lg border-2 border-slate-200 transition
                                          hover:border-blue-300 hover:bg-blue-50/50
                                          has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                <input type="radio" name="business_act" value="{{ $value }}"
                                       {{ old('business_act') == $value ? 'checked' : '' }}
                                       class="w-4 h-4 text-blue-600 border-slate-300 focus:ring-blue-500 mt-0.5" />
                                <div>
                                    <div class="flex items-center gap-1.5">
                                        <i class="bi {{ $icon }} text-slate-500 text-sm"></i>
                                        <span class="text-sm font-medium text-slate-700">{{ $value }}</span>
                                    </div>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $desc }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('business_act')
                            <p class="text-xs text-red-600 flex items-center gap-1 mt-2">
                                <i class="bi bi-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div class="bg-slate-50 border-t border-slate-200 px-6 py-4 flex justify-between">
                    <button type="button" @click="currentStep = 2"
                            class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-6 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                        <i class="bi bi-arrow-left"></i> Previous
                    </button>
                    <button type="button" @click="currentStep = 4"
                            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700">
                        Next Step <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- ============================================================== --}}
        {{-- STEP 4: Document Upload (4 Separate File Inputs) --}}
        {{-- ============================================================== --}}
        <div x-show="currentStep === 4" x-cloak>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 border-b border-slate-200 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100">
                            <i class="bi bi-cloud-arrow-up text-orange-600 text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-slate-800">Upload Documents</h2>
                            <p class="text-sm text-slate-500">Attach the required documents for your application</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 space-y-6">

                    {{-- Required Documents Info --}}
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <i class="bi bi-info-circle text-blue-600 text-lg mt-0.5"></i>
                            <div>
                                <h4 class="text-sm font-semibold text-blue-800 mb-1">All 4 documents are required</h4>
                                <p class="text-xs text-blue-600">Accepted formats: JPEG, PNG, PDF — Max 10MB each</p>
                            </div>
                        </div>
                    </div>

                    {{-- 4 Separate Upload Fields --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                        {{-- 1. DTI/SEC Certificate --}}
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-700">
                                <i class="bi bi-file-earmark-text text-slate-400 mr-1"></i>
                                DTI/SEC Certificate <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="file" name="dti_sec_certificate" accept=".jpg,.jpeg,.png,.pdf"
                                       class="block w-full text-sm text-slate-500 
                                              file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 
                                              file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 
                                              hover:file:bg-blue-100 file:cursor-pointer
                                              border border-slate-300 rounded-lg cursor-pointer
                                              {{ $errors->has('dti_sec_certificate') ? 'border-red-400' : '' }}" />
                            </div>
                            @error('dti_sec_certificate')
                                <p class="text-xs text-red-600 flex items-center gap-1">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- 2. Valid ID with 3 Signatures --}}
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-700">
                                <i class="bi bi-person-badge text-slate-400 mr-1"></i>
                                Valid ID with 3 Signatures <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="file" name="valid_id" accept=".jpg,.jpeg,.png,.pdf"
                                       class="block w-full text-sm text-slate-500 
                                              file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 
                                              file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 
                                              hover:file:bg-blue-100 file:cursor-pointer
                                              border border-slate-300 rounded-lg cursor-pointer
                                              {{ $errors->has('valid_id') ? 'border-red-400' : '' }}" />
                            </div>
                            @error('valid_id')
                                <p class="text-xs text-red-600 flex items-center gap-1">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- 3. Photo of Business --}}
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-700">
                                <i class="bi bi-camera text-slate-400 mr-1"></i>
                                Photo of Business <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="file" name="business_photo" accept=".jpg,.jpeg,.png,.gif"
                                       class="block w-full text-sm text-slate-500 
                                              file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 
                                              file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 
                                              hover:file:bg-blue-100 file:cursor-pointer
                                              border border-slate-300 rounded-lg cursor-pointer
                                              {{ $errors->has('business_photo') ? 'border-red-400' : '' }}" />
                            </div>
                            @error('business_photo')
                                <p class="text-xs text-red-600 flex items-center gap-1">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- 4. Business Sketch --}}
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-700">
                                <i class="bi bi-map text-slate-400 mr-1"></i>
                                Business Location Sketch <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="file" name="business_sketch" accept=".jpg,.jpeg,.png,.pdf"
                                       class="block w-full text-sm text-slate-500 
                                              file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 
                                              file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 
                                              hover:file:bg-blue-100 file:cursor-pointer
                                              border border-slate-300 rounded-lg cursor-pointer
                                              {{ $errors->has('business_sketch') ? 'border-red-400' : '' }}" />
                            </div>
                            @error('business_sketch')
                                <p class="text-xs text-red-600 flex items-center gap-1">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Submit --}}
                <div class="bg-slate-50 border-t border-slate-200 px-6 py-4 flex justify-between">
                    <button type="button" @click="currentStep = 3"
                            class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-6 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                        <i class="bi bi-arrow-left"></i> Previous
                    </button>
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-8 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700">
                        <i class="bi bi-send"></i> Submit Application
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection