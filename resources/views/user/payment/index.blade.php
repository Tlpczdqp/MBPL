@extends('layouts.app')
@section('title', 'Submit Payment')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Submit Payment</h1>
        <p class="text-sm text-slate-500 mt-1">
            Application: <span class="font-semibold text-slate-700">{{ $application->application_number }}</span>
        </p>
    </div>

    {{-- Fee Card --}}
    <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl p-6 text-white mb-6 shadow-lg">
        <p class="text-sm text-blue-200 mb-1">Amount Due</p>
        <p class="text-4xl font-bold">₱{{ number_format($application->permit_fee, 2) }}</p>
        <p class="text-sm text-blue-200 mt-2">Business: {{ $application->business_name }}</p>
    </div>

    {{-- Payment Form --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <form method="POST"
              action="{{ route('user.payment.submit', ['userId' => auth()->id(), 'application' => $application->id]) }}"
              enctype="multipart/form-data"
              class="space-y-5">
            @csrf

            {{-- Payment Method --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-3">
                    Select Payment Method <span class="text-red-500">*</span>
                </label>

                {{-- Payment method cards --}}
                <div class="grid grid-cols-3 gap-3" x-data="{ selected: '{{ old('payment_method') }}' }">

                    {{-- GCash --}}
                    <label class="relative cursor-pointer">
                        <input type="radio" name="payment_method" value="gcash"
                               x-model="selected" class="sr-only" />
                        <div :class="selected === 'gcash'
                                        ? 'border-blue-500 bg-blue-50 ring-2 ring-blue-500'
                                        : 'border-slate-200 hover:border-slate-300'"
                             class="border-2 rounded-xl p-4 text-center transition-all">
                            <div class="text-2xl mb-1">📱</div>
                            <p class="text-xs font-bold text-slate-800">GCash</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">0917-XXX-XXXX</p>
                        </div>
                    </label>

                    {{-- PayMaya --}}
                    <label class="relative cursor-pointer">
                        <input type="radio" name="payment_method" value="paymaya"
                               x-model="selected" class="sr-only" />
                        <div :class="selected === 'paymaya'
                                        ? 'border-green-500 bg-green-50 ring-2 ring-green-500'
                                        : 'border-slate-200 hover:border-slate-300'"
                             class="border-2 rounded-xl p-4 text-center transition-all">
                            <div class="text-2xl mb-1">💳</div>
                            <p class="text-xs font-bold text-slate-800">PayMaya</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">0918-XXX-XXXX</p>
                        </div>
                    </label>

                    {{-- Bank Transfer --}}
                    <label class="relative cursor-pointer">
                        <input type="radio" name="payment_method" value="bank_transfer"
                               x-model="selected" class="sr-only" />
                        <div :class="selected === 'bank_transfer'
                                        ? 'border-purple-500 bg-purple-50 ring-2 ring-purple-500'
                                        : 'border-slate-200 hover:border-slate-300'"
                             class="border-2 rounded-xl p-4 text-center transition-all">
                            <div class="text-2xl mb-1">🏦</div>
                            <p class="text-xs font-bold text-slate-800">Bank Transfer</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">BDO / BPI</p>
                        </div>
                    </label>

                </div>
                @error('payment_method')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Reference Number --}}
            <div>
                <label for="reference_number"
                       class="block text-sm font-semibold text-slate-700 mb-1">
                    Reference Number <span class="text-red-500">*</span>
                </label>
                <p class="text-xs text-slate-400 mb-2">
                    Enter the transaction/reference number from your payment.
                </p>
                <input id="reference_number"
                       type="text"
                       name="reference_number"
                       value="{{ old('reference_number') }}"
                       placeholder="e.g. 1234567890"
                       class="w-full px-4 py-2.5 text-sm rounded-lg border border-slate-300
                              text-slate-900 placeholder:text-slate-400
                              focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />
                @error('reference_number')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Proof of Payment Upload --}}
            <div x-data="{
                    fileName: '',
                    previewUrl: '',
                    handleFile(e) {
                        const file = e.target.files[0];
                        if (file) {
                            this.fileName = file.name;
                            this.previewUrl = URL.createObjectURL(file);
                        }
                    }
                }">
                <label class="block text-sm font-semibold text-slate-700 mb-1">
                    Proof of Payment <span class="text-red-500">*</span>
                </label>
                <p class="text-xs text-slate-400 mb-2">
                    Upload a screenshot of your payment confirmation. (JPG, PNG, max 2MB)
                </p>

                {{-- Drop zone --}}
                <div @click="$refs.proofInput.click()"
                     class="border-2 border-dashed border-slate-300 rounded-xl p-8 text-center
                            cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition-all">

                    {{-- Preview image if selected --}}
                    <template x-if="previewUrl">
                        <img :src="previewUrl" alt="Preview"
                             class="max-h-40 mx-auto rounded-lg object-contain mb-2" />
                    </template>

                    <template x-if="!previewUrl">
                        <div>
                            <i class="bi bi-cloud-arrow-up text-3xl text-slate-300 block mb-2"></i>
                            <p class="text-sm text-slate-600 font-medium">Click to upload screenshot</p>
                            <p class="text-xs text-slate-400 mt-1">JPG, PNG up to 2MB</p>
                        </div>
                    </template>

                    <p x-show="fileName" x-text="fileName"
                       class="text-xs text-blue-600 font-medium mt-2"></p>

                    <input type="file"
                           name="proof_image"
                           x-ref="proofInput"
                           @change="handleFile($event)"
                           accept=".jpg,.jpeg,.png"
                           class="hidden" />
                </div>
                @error('proof_image')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit --}}
            <div class="flex gap-3 pt-2">
                <a href="{{ route('user.business.show', ['userId' => auth()->id(), 'application' => $application->id]) }}"
                   class="flex-1 py-2.5 text-center border border-slate-300 text-slate-700
                          text-sm font-semibold rounded-lg hover:bg-slate-50 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="flex-1 py-2.5 bg-green-600 hover:bg-green-700 text-white
                               text-sm font-semibold rounded-lg transition-colors">
                    <i class="bi bi-send mr-1"></i>
                    Submit Payment
                </button>
            </div>

        </form>
    </div>
</div>
@endsection