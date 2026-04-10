{{-- resources/views/user/applications/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Application ' . $application->application_number)

@section('content')
<div x-data="{ 
    activeTab: 'details',
    showDeleteModal: false,
    showImageModal: false,
    previewUrl: '',
    previewName: '',
}">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <h1 class="text-2xl font-bold text-slate-800">{{ $application->application_number }}</h1>
                @php
                    $statusConfig = [
                        'pending'        => ['label' => 'Pending',        'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'icon' => 'bi-clock'],
                        'under_review'   => ['label' => 'Under Review',   'bg' => 'bg-blue-100',   'text' => 'text-blue-700',   'icon' => 'bi-search'],
                        'approved'       => ['label' => 'Approved',       'bg' => 'bg-green-100',  'text' => 'text-green-700',  'icon' => 'bi-check-circle'],
                        'rejected'       => ['label' => 'Rejected',       'bg' => 'bg-red-100',    'text' => 'text-red-700',    'icon' => 'bi-x-circle'],
                        'paid'           => ['label' => 'Paid',           'bg' => 'bg-emerald-100','text' => 'text-emerald-700','icon' => 'bi-credit-card'],
                        'permit_issued'  => ['label' => 'Permit Issued',  'bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'icon' => 'bi-award'],
                    ];
                    $status = $statusConfig[$application->status] ?? ['label' => ucfirst($application->status), 'bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'icon' => 'bi-question-circle'];
                @endphp
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $status['bg'] }} {{ $status['text'] }}">
                    <i class="bi {{ $status['icon'] }}"></i>
                    {{ $status['label'] }}
                </span>
            </div>
            <p class="text-sm text-slate-500">
                Submitted on {{ $application->created_at->format('F d, Y \a\t h:i A') }}
            </p>
        </div>

        <div class="mt-3 sm:mt-0 flex items-center gap-3 flex-wrap">
            @if($application->status === 'permit_issued')
                <a href="{{ route('user.permit.print', ['userId' => Auth::id(), 'application' => $application->id]) }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-purple-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-purple-700">
                    <i class="bi bi-printer"></i> Print Permit
                </a>
            @endif

            {{-- ── TOP HEADER Pay Now button ──────────────────────────── --}}
@if($application->status === 'approved' && $application->permit_fee)
    {{-- ✅ This is GET — goes to payment page first --}}
    <a href="{{ route('user.payment.show', [
            'userId'      => Auth::id(),
            'application' => $application->id
        ]) }}"
       class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-green-700">
        <i class="bi bi-credit-card"></i> Pay Now
    </a>
@endif

            @if($application->status === 'permit_issued')
                <a href="{{ route('user.business.renew', ['userId' => Auth::id(), 'application' => $application->id]) }}"
                   class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                    <i class="bi bi-arrow-repeat"></i> Renew
                </a>
            @endif

            {{-- DELETE BUTTON — only for pending or rejected --}}
            @if(in_array($application->status, ['pending', 'rejected']))
                <button type="button" @click="showDeleteModal = true"
                        class="inline-flex items-center gap-2 rounded-lg border border-red-300 bg-white px-4 py-2.5 text-sm font-medium text-red-600 shadow-sm transition hover:bg-red-50">
                    <i class="bi bi-trash3"></i> Delete
                </button>
            @endif

            <a href="{{ route('user.business.index', ['userId' => Auth::id()]) }}"
               class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    {{-- Status Timeline --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
        <h3 class="text-sm font-semibold text-slate-700 mb-4">Application Progress</h3>
        <div class="flex items-center justify-between">
            @php
                $steps = [
                    ['key' => 'pending',       'label' => 'Submitted',     'icon' => 'bi-send'],
                    ['key' => 'under_review',  'label' => 'Under Review',  'icon' => 'bi-search'],
                    ['key' => 'approved',      'label' => 'Approved',      'icon' => 'bi-check-circle'],
                    ['key' => 'paid',          'label' => 'Paid',          'icon' => 'bi-credit-card'],
                    ['key' => 'permit_issued', 'label' => 'Permit Issued', 'icon' => 'bi-award'],
                ];
                $statusOrder = ['pending' => 1, 'under_review' => 2, 'approved' => 3, 'rejected' => 0, 'paid' => 4, 'permit_issued' => 5];
                $currentOrder = $statusOrder[$application->status] ?? 0;
            @endphp

            @foreach($steps as $index => $step)
                @php
                    $stepOrder = $statusOrder[$step['key']] ?? 0;
                    $isCompleted = $currentOrder > $stepOrder;
                    $isCurrent = $currentOrder === $stepOrder;
                    $isRejected = $application->status === 'rejected' && $step['key'] === 'approved';
                @endphp
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition
                        @if($isRejected) bg-red-100 text-red-600 ring-2 ring-red-300
                        @elseif($isCompleted) bg-blue-600 text-white
                        @elseif($isCurrent) bg-blue-100 text-blue-600 ring-2 ring-blue-300
                        @else bg-slate-100 text-slate-400
                        @endif">
                        @if($isRejected) <i class="bi bi-x-lg"></i>
                        @elseif($isCompleted) <i class="bi bi-check-lg"></i>
                        @else <i class="bi {{ $step['icon'] }}"></i>
                        @endif
                    </div>
                    <span class="text-xs font-medium mt-2 text-center
                        @if($isRejected) text-red-600
                        @elseif($isCompleted || $isCurrent) text-slate-800
                        @else text-slate-400
                        @endif">
                        @if($isRejected) Rejected @else {{ $step['label'] }} @endif
                    </span>
                </div>
                @if(!$loop->last)
                    <div class="flex-1 h-0.5 mx-2 rounded
                        @if($isRejected) bg-red-300
                        @elseif($isCompleted) bg-blue-600
                        @else bg-slate-200
                        @endif"></div>
                @endif
            @endforeach
        </div>
    </div>

    {{-- Rejection Reason --}}
    @if($application->status === 'rejected' && $application->rejection_reason)
    <div class="bg-red-50 border border-red-200 rounded-xl p-5 mb-6">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                <i class="bi bi-exclamation-triangle text-red-600 text-lg"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-red-800">Application Rejected</h3>
                <p class="text-sm text-red-700 mt-1">{{ $application->rejection_reason }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Permit Fee Alert --}}
    @if($application->status === 'approved' && $application->permit_fee)
    <div class="bg-green-50 border border-green-200 rounded-xl p-5 mb-6">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                <i class="bi bi-cash-coin text-green-600 text-lg"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-green-800">Payment Required</h3>
                <p class="text-sm text-green-700 mt-1">
                    Your permit fee is <strong>₱{{ number_format((float) $application->permit_fee, 2) }}</strong>. Please proceed to payment.
                </p>
            </div>
        </div>
    </div>
    @endif

    {{-- Permit Issued Alert --}}
    @if($application->status === 'permit_issued')
    <div class="bg-purple-50 border border-purple-200 rounded-xl p-5 mb-6">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center flex-shrink-0">
                <i class="bi bi-award text-purple-600 text-lg"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-purple-800">Permit Issued! 🎉</h3>
                <p class="text-sm text-purple-700 mt-1">
                    Issued on <strong>{{ \Carbon\Carbon::parse($application->permit_issued_at)->format('F d, Y') }}</strong>
                    — Valid until <strong>{{ \Carbon\Carbon::parse($application->permit_valid_until)->format('F d, Y') }}</strong>
                </p>
            </div>
        </div>
    </div>
    @endif

    {{-- Tab Navigation --}}
    <div class="bg-white rounded-t-xl shadow-sm border border-slate-200 border-b-0">
        <div class="flex border-b border-slate-200 overflow-x-auto">
            <button type="button" @click="activeTab = 'details'"
                    class="px-6 py-3.5 text-sm font-medium transition border-b-2 -mb-px whitespace-nowrap"
                    :class="activeTab === 'details' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700'">
                <i class="bi bi-building mr-1.5"></i> Business Details
            </button>
            <button type="button" @click="activeTab = 'address'"
                    class="px-6 py-3.5 text-sm font-medium transition border-b-2 -mb-px whitespace-nowrap"
                    :class="activeTab === 'address' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700'">
                <i class="bi bi-geo-alt mr-1.5"></i> Address & Owner
            </button>
            <button type="button" @click="activeTab = 'documents'"
                    class="px-6 py-3.5 text-sm font-medium transition border-b-2 -mb-px whitespace-nowrap"
                    :class="activeTab === 'documents' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700'">
                <i class="bi bi-file-earmark mr-1.5"></i> Documents
                <span class="ml-1 bg-slate-200 text-slate-600 text-xs font-bold px-1.5 py-0.5 rounded-full">{{ $application->documents->count() }}</span>
            </button>
            <button type="button" @click="activeTab = 'payment'"
                    class="px-6 py-3.5 text-sm font-medium transition border-b-2 -mb-px whitespace-nowrap"
                    :class="activeTab === 'payment' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700'">
                <i class="bi bi-credit-card mr-1.5"></i> Payment
            </button>
        </div>
    </div>

    {{-- Tab Content --}}
    <div class="bg-white rounded-b-xl shadow-sm border border-slate-200 border-t-0">

        {{-- ============================================================ --}}
        {{-- TAB: Business Details --}}
        {{-- ============================================================ --}}
        <div x-show="activeTab === 'details'" class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-800 mb-3 flex items-center gap-2">
                            <i class="bi bi-file-text text-slate-400"></i> Application Information
                        </h3>
                        <div class="bg-slate-50 rounded-lg divide-y divide-slate-200">
                            @foreach([
                                ['Application Number', $application->application_number],
                                ['Transaction Type', $application->transact_type],
                                ['Billing Frequency', $application->billing_freq],
                                ['Business Type', $application->business_info],
                                ['Status', $status['label']],
                                ['Date Submitted', $application->created_at->format('M d, Y h:i A')],
                            ] as [$label, $value])
                            <div class="flex items-center justify-between px-4 py-3">
                                <span class="text-sm text-slate-500">{{ $label }}</span>
                                <span class="text-sm font-medium text-slate-800">{{ $value }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-800 mb-3 flex items-center gap-2">
                            <i class="bi bi-building text-slate-400"></i> Business Information
                        </h3>
                        <div class="bg-slate-50 rounded-lg divide-y divide-slate-200">
                            @foreach([
                                ['Business Name', $application->business_name],
                                ['Trade Name', $application->trade_name ?: '—'],
                                ['Registration No.', $application->reg_num],
                                ['TIN', $application->business_tin],
                                ['Business Activity', $application->business_act],
                            ] as [$label, $value])
                            <div class="flex items-center justify-between px-4 py-3">
                                <span class="text-sm text-slate-500">{{ $label }}</span>
                                <span class="text-sm font-medium text-slate-800">{{ $value }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-slate-800 mb-3 flex items-center gap-2">
                            <i class="bi bi-telephone text-slate-400"></i> Contact Information
                        </h3>
                        <div class="bg-slate-50 rounded-lg divide-y divide-slate-200">
                            @foreach([
                                ['Telephone', $application->telephone_num ?: '—'],
                                ['Phone', $application->phone_number],
                                ['Email', $application->business_email],
                            ] as [$label, $value])
                            <div class="flex items-center justify-between px-4 py-3">
                                <span class="text-sm text-slate-500">{{ $label }}</span>
                                <span class="text-sm font-medium text-slate-800">{{ $value }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- TAB: Address & Owner --}}
        {{-- ============================================================ --}}
        <div x-show="activeTab === 'address'" x-cloak class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-sm font-semibold text-slate-800 mb-3 flex items-center gap-2">
                        <i class="bi bi-geo-alt text-slate-400"></i> Business Address
                    </h3>
                    <div class="bg-slate-50 rounded-lg divide-y divide-slate-200">
                        @foreach([
                            ['House/Bldg No.', $application->house_num],
                            ['Building Name', $application->building_name],
                            ['Lot No.', $application->lot_num],
                            ['Block No.', $application->block_num],
                            ['Street', $application->street],
                            ['Barangay', $application->barangay],
                            ['Subdivision', $application->subdivision],
                            ['City/Municipality', $application->city_muni],
                            ['Province', $application->province],
                            ['Zip Code', $application->zip_code],
                        ] as [$label, $value])
                            @if($value)
                            <div class="flex items-center justify-between px-4 py-3">
                                <span class="text-sm text-slate-500">{{ $label }}</span>
                                <span class="text-sm font-medium text-slate-800">{{ $value }}</span>
                            </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-xs font-semibold text-blue-700 mb-1">FULL ADDRESS</p>
                        <p class="text-sm text-blue-800">
                            {{ collect([
                                $application->house_num, $application->building_name,
                                $application->street, $application->barangay,
                                $application->subdivision, $application->city_muni,
                                $application->province, $application->zip_code,
                            ])->filter()->implode(', ') }}
                        </p>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-slate-800 mb-3 flex items-center gap-2">
                        <i class="bi bi-person text-slate-400"></i> Owner / Officer Information
                    </h3>
                    @if($application->business_info === 'Sole Proprietorship')
                        <div class="bg-slate-50 rounded-lg divide-y divide-slate-200">
                            <div class="px-4 py-3">
                                <span class="text-xs font-medium text-slate-400 uppercase tracking-wide">Ownership Type</span>
                                <p class="text-sm font-medium text-slate-800 mt-0.5">Sole Proprietorship</p>
                            </div>
                            <div class="px-4 py-3">
                                <span class="text-xs font-medium text-slate-400 uppercase tracking-wide">Owner Name</span>
                                <p class="text-sm font-medium text-slate-800 mt-0.5">
                                    {{ collect([$application->sp_owner_fname, $application->sp_owner_mname, $application->sp_owner_lname])->filter()->implode(' ') ?: '—' }}
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="bg-slate-50 rounded-lg divide-y divide-slate-200">
                            <div class="px-4 py-3">
                                <span class="text-xs font-medium text-slate-400 uppercase tracking-wide">Ownership Type</span>
                                <p class="text-sm font-medium text-slate-800 mt-0.5">{{ $application->business_info }}</p>
                            </div>
                            <div class="px-4 py-3">
                                <span class="text-xs font-medium text-slate-400 uppercase tracking-wide">President / Officer</span>
                                <p class="text-sm font-medium text-slate-800 mt-0.5">
                                    {{ collect([$application->corp_owner_fname, $application->corp_owner_mname, $application->corp_owner_lname])->filter()->implode(' ') ?: '—' }}
                                </p>
                            </div>
                            @if($application->corp_location)
                            <div class="px-4 py-3">
                                <span class="text-xs font-medium text-slate-400 uppercase tracking-wide">Corporation Type</span>
                                <p class="text-sm font-medium text-slate-800 mt-0.5">{{ $application->corp_location }}</p>
                            </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- TAB: Documents (with View & Preview)                          --}}
        {{-- ============================================================ --}}
        <div x-show="activeTab === 'documents'" x-cloak class="p-6">
            <h3 class="text-sm font-semibold text-slate-800 mb-4 flex items-center gap-2">
                <i class="bi bi-file-earmark text-slate-400"></i>
                Uploaded Documents
                <span class="bg-slate-200 text-slate-600 text-xs font-bold px-2 py-0.5 rounded-full">{{ $application->documents->count() }}</span>
            </h3>

            @if($application->documents->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($application->documents as $doc)
                    @php
                        $docLabels = [
                            'dti_sec_certificate' => ['DTI/SEC Certificate', 'bi-file-earmark-text', 'bg-blue-100', 'text-blue-600'],
                            'valid_id'            => ['Valid ID', 'bi-person-badge', 'bg-green-100', 'text-green-600'],
                            'business_photo'      => ['Business Photo', 'bi-camera', 'bg-purple-100', 'text-purple-600'],
                            'business_sketch'     => ['Business Sketch', 'bi-map', 'bg-orange-100', 'text-orange-600'],
                        ];
                        $docInfo = $docLabels[$doc->document_type] ?? ['Document', 'bi-file-earmark', 'bg-slate-100', 'text-slate-600'];
                        $isImage = in_array($doc->mime_type, ['image/jpeg', 'image/png', 'image/gif']);
                        $isPdf = $doc->mime_type === 'application/pdf';
                        $viewUrl = route('user.business.document.view', [
                            'userId'      => Auth::id(),
                            'application' => $application->id,
                            'document'    => $doc->id,
                        ]);
                    @endphp

                    <div class="rounded-lg border border-slate-200 bg-white shadow-sm hover:shadow-md transition overflow-hidden">
                        {{-- Document Preview Thumbnail --}}
                        @if($isImage)
                        <div class="relative h-48 bg-slate-100 cursor-pointer overflow-hidden group"
                             @click="previewUrl = '{{ $viewUrl }}'; previewName = '{{ $docInfo[0] }}'; showImageModal = true">
                            <img src="{{ $viewUrl }}" 
                                 alt="{{ $docInfo[0] }}"
                                 class="h-full w-full object-cover group-hover:scale-105 transition duration-300" 
                                 loading="lazy" />
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition flex items-center justify-center">
                                <span class="opacity-0 group-hover:opacity-100 transition bg-white/90 rounded-full px-3 py-1.5 text-xs font-medium text-slate-700 shadow">
                                    <i class="bi bi-zoom-in mr-1"></i> Preview
                                </span>
                            </div>
                        </div>
                        @elseif($isPdf)
                        <div class="h-40 bg-red-50 flex flex-col items-center justify-center">
                            <i class="bi bi-file-earmark-pdf text-4xl text-red-400"></i>
                            <span class="text-xs text-red-400 mt-1">PDF Document</span>
                        </div>
                        @else
                        <div class="h-40 bg-slate-50 flex flex-col items-center justify-center">
                            <i class="bi bi-file-earmark text-4xl text-slate-300"></i>
                            <span class="text-xs text-slate-400 mt-1">{{ strtoupper(pathinfo($doc->file_name, PATHINFO_EXTENSION)) }}</span>
                        </div>
                        @endif

                        {{-- Document Info & Actions --}}
                        <div class="p-4">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-lg {{ $docInfo[2] }} flex items-center justify-center flex-shrink-0">
                                    <i class="bi {{ $docInfo[1] }} {{ $docInfo[3] }} text-base"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-slate-800">{{ $docInfo[0] }}</p>
                                    <p class="text-xs text-slate-400 truncate">{{ $doc->file_name }}</p>
                                    <p class="text-xs text-slate-400">
                                        {{ number_format($doc->file_size / 1024, 0) }} KB
                                        · {{ strtoupper(pathinfo($doc->file_name, PATHINFO_EXTENSION)) }}
                                    </p>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex items-center gap-2 mt-3 pt-3 border-t border-slate-100">
                                {{-- View / Open in new tab --}}
                                <a href="{{ $viewUrl }}" target="_blank"
                                   class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-700 transition hover:bg-slate-50">
                                    <i class="bi bi-eye"></i> View
                                </a>

                                {{-- Download --}}
                                <a href="{{ $viewUrl }}" download="{{ $doc->file_name }}"
                                   class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-lg border border-blue-300 bg-blue-50 px-3 py-2 text-xs font-medium text-blue-700 transition hover:bg-blue-100">
                                    <i class="bi bi-download"></i> Download
                                </a>

                                {{-- Preview (images only) --}}
                                @if($isImage)
                                <button type="button" 
                                        @click="previewUrl = '{{ $viewUrl }}'; previewName = '{{ $docInfo[0] }}'; showImageModal = true"
                                        class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-purple-300 bg-purple-50 px-3 py-2 text-xs font-medium text-purple-700 transition hover:bg-purple-100">
                                    <i class="bi bi-zoom-in"></i>
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                        <i class="bi bi-file-earmark-x text-3xl text-slate-300"></i>
                    </div>
                    <p class="text-sm font-medium text-slate-500">No documents uploaded</p>
                </div>
            @endif
        </div>

        {{-- ============================================================ --}}
        {{-- TAB: Payment --}}
        {{-- ============================================================ --}}
        <div x-show="activeTab === 'payment'" x-cloak class="p-6">
            <h3 class="text-sm font-semibold text-slate-800 mb-4 flex items-center gap-2">
                <i class="bi bi-credit-card text-slate-400"></i> Payment Information
            </h3>
            
            @if($application->payment)
                @php
                    $paymentStatusConfig = [
                        'pending'  => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Pending'],
                        'verified' => ['bg' => 'bg-green-100',  'text' => 'text-green-700',  'label' => 'Verified'],
                        'rejected' => ['bg' => 'bg-red-100',    'text' => 'text-red-700',    'label' => 'Rejected'],
                    ];
                    $payStatus = $paymentStatusConfig[$application->payment->status] ?? ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'label' => ucfirst($application->payment->status)];
                @endphp
                <div class="bg-slate-50 rounded-lg divide-y divide-slate-200">
                    <div class="flex items-center justify-between px-4 py-3">
                        <span class="text-sm text-slate-500">Payment Status</span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $payStatus['bg'] }} {{ $payStatus['text'] }}">
                            {{ $payStatus['label'] }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between px-4 py-3">
                        <span class="text-sm text-slate-500">Permit Fee</span>
                        <span class="text-sm font-bold text-slate-800">₱{{ number_format((float) $application->permit_fee, 2) }}</span>
                    </div>
                    @if($application->payment->payment_method ?? null)
                    <div class="flex items-center justify-between px-4 py-3">
                        <span class="text-sm text-slate-500">Payment Method</span>
                        <span class="text-sm font-medium text-slate-800">{{ ucfirst($application->payment->payment_method) }}</span>
                    </div>
                    @endif
                    @if($application->payment->reference_number ?? null)
                    <div class="flex items-center justify-between px-4 py-3">
                        <span class="text-sm text-slate-500">Reference Number</span>
                        <span class="text-sm font-medium text-slate-800 font-mono">{{ $application->payment->reference_number }}</span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between px-4 py-3">
                        <span class="text-sm text-slate-500">Date Paid</span>
                        <span class="text-sm font-medium text-slate-800">{{ $application->payment->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                </div>

            @elseif($application->status === 'approved' && $application->permit_fee)
                <div class="text-center py-12">
                    <div class="w-16 h-16 rounded-full bg-yellow-100 flex items-center justify-center mx-auto mb-4">
                        <i class="bi bi-cash-coin text-3xl text-yellow-500"></i>
                    </div>
                    <p class="text-sm font-medium text-slate-700">Awaiting Payment</p>
                    <p class="text-xs text-slate-400 mt-1 mb-4">
                        Permit fee: <strong class="text-slate-700">₱{{ number_format((float) $application->permit_fee, 2) }}</strong>
                    </p>
                    <a href="{{ route('user.payment.show', ['userId' => Auth::id(), 'application' => $application->id]) }}"
                       class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-green-700">
                        <i class="bi bi-credit-card"></i> Pay Now
                    </a>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                        <i class="bi bi-credit-card text-3xl text-slate-300"></i>
                    </div>
                    <p class="text-sm font-medium text-slate-500">No payment information yet</p>
                    <p class="text-xs text-slate-400 mt-1">Payment details will appear once your application is approved</p>
                </div>
            @endif
            {{-- Only show Pay button when status is approved and fee is set --}}
            @if ($application->status === 'approved' && $application->permit_fee)
                <a href="{{ route('user.payment.show', ['userId' => Auth::id(), 'application' => $application->id]) }}"
                    class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-xl transition shadow-sm text-sm">
                    <i class="bi bi-credit-card-2-front"></i>
                    Pay Permit Fee — ₱{{ number_format($application->permit_fee, 2) }}
                </a>
            @elseif($application->status === 'paid')
                <div
                    class="inline-flex items-center gap-2 bg-purple-100 text-purple-700 font-semibold px-6 py-3 rounded-xl text-sm">
                    <i class="bi bi-check-circle-fill"></i>
                    Payment Received — Processing Permit
                </div>
            @elseif($application->status === 'permit_issued')
                <a href="{{ route('user.permit.print', ['userId' => Auth::id(), 'application' => $application->id]) }}"
                    target="_blank"
                    class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-6 py-3 rounded-xl transition shadow-sm text-sm">
                    <i class="bi bi-printer"></i>
                    Print Permit
                </a>
            @else
                 {{-- pending, under_review, rejected —  no pay button --}}
                <div
                    class="inline-flex items-center gap-2 bg-slate-100 text-slate-500 font-medium px-6 py-3 rounded-xl text-sm">
                    <i class="bi bi-hourglass-split"></i>
                    Payment unavailable — awaiting approval
                </div>
            @endif
        </div>

    </div>

    {{-- Processed By --}}
    @if($application->processedBy)
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 mt-6">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center">
                <i class="bi bi-person-gear text-slate-500"></i>
            </div>
            <div>
                <p class="text-xs text-slate-400">Processed by</p>
                <p class="text-sm font-semibold text-slate-800">{{ $application->processedBy->name }}</p>
            </div>
            <div class="ml-auto text-right">
                <p class="text-xs text-slate-400">Last updated</p>
                <p class="text-sm font-medium text-slate-600">{{ $application->updated_at->format('M d, Y h:i A') }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- ================================================================ --}}
    {{-- IMAGE PREVIEW MODAL                                               --}}
    {{-- ================================================================ --}}
    <div x-show="showImageModal" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         @keydown.escape.window="showImageModal = false">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/70" @click="showImageModal = false"></div>

        {{-- Modal Content --}}
        <div class="relative z-10 w-full max-w-4xl max-h-[90vh] bg-white rounded-2xl shadow-2xl overflow-hidden"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
                        <i class="bi bi-image text-purple-600"></i>
                    </div>
                    <h3 class="text-sm font-semibold text-slate-800" x-text="previewName"></h3>
                </div>
                <div class="flex items-center gap-2">
                    {{-- Open in new tab --}}
                    <a :href="previewUrl" target="_blank"
                       class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-50">
                        <i class="bi bi-box-arrow-up-right"></i> Open
                    </a>
                    {{-- Download --}}
                    <a :href="previewUrl" download
                       class="inline-flex items-center gap-1.5 rounded-lg border border-blue-300 bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-700 transition hover:bg-blue-100">
                        <i class="bi bi-download"></i> Download
                    </a>
                    {{-- Close --}}
                    <button @click="showImageModal = false"
                            class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>

            {{-- Image --}}
            <div class="p-4 flex items-center justify-center bg-slate-100 overflow-auto" style="max-height: calc(90vh - 80px);">
                <img :src="previewUrl" :alt="previewName" class="max-w-full max-h-full object-contain rounded-lg shadow-sm" />
            </div>
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- DELETE CONFIRMATION MODAL                                         --}}
    {{-- ================================================================ --}}
    <div x-show="showDeleteModal" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         @keydown.escape.window="showDeleteModal = false">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/50" @click="showDeleteModal = false"></div>

        {{-- Modal --}}
        <div class="relative z-10 w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            <div class="p-6 text-center">
                {{-- Warning Icon --}}
                <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                    <i class="bi bi-exclamation-triangle text-3xl text-red-500"></i>
                </div>

                <h3 class="text-lg font-bold text-slate-800 mb-2">Delete Application?</h3>
                <p class="text-sm text-slate-500 mb-1">
                    You are about to delete application
                </p>
                <p class="text-sm font-bold text-slate-700 mb-4">
                    {{ $application->application_number }}
                </p>

                <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-6 text-left">
                    <div class="flex items-start gap-2">
                        <i class="bi bi-info-circle text-red-500 mt-0.5"></i>
                        <div class="text-xs text-red-700">
                            <p class="font-semibold mb-1">This action will permanently:</p>
                            <ul class="space-y-0.5 list-disc list-inside">
                                <li>Delete the application record</li>
                                <li>Delete all {{ $application->documents->count() }} uploaded document(s)</li>
                                <li>Delete any payment records</li>
                                <li>Delete related notifications</li>
                            </ul>
                            <p class="mt-2 font-bold">This cannot be undone.</p>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-3">
                    <button type="button" @click="showDeleteModal = false"
                            class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                        Cancel
                    </button>
                    <form action="{{ route('user.business.destroy', ['userId' => Auth::id(), 'application' => $application->id]) }}" 
                          method="POST" 
                          class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-red-700">
                            <i class="bi bi-trash3"></i> Yes, Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection