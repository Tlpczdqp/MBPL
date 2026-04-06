{{--
    This is the page employees use to REVIEW an application.
    Manager/Admin can APPROVE or REJECT from here.
    It shows all submitted documents and application details.
--}}
@extends('layouts.employee')
@section('title', 'Review Application')

@section('content')
<div class="max-w-5xl mx-auto">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-4">
        <a href="{{ route('employee.manager.applications.index') }}" class="hover:text-slate-700">
            Applications
        </a>
        <i class="bi bi-chevron-right text-xs"></i>
        <span class="text-slate-700 font-medium">{{ $application->application_number }}</span>
    </div>

    {{-- Header --}}
    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">{{ $application->business_name }}</h1>
            <p class="text-sm text-slate-500 mt-1">
                Application #: <span class="font-mono font-semibold">{{ $application->application_number }}</span>
                · Submitted {{ $application->created_at->diffForHumans() }}
            </p>
        </div>

        {{-- Current Status Badge --}}
        @php
            $statusClasses = [
                'pending'       => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                'under_review'  => 'bg-blue-100 text-blue-700 border-blue-200',
                'approved'      => 'bg-green-100 text-green-700 border-green-200',
                'rejected'      => 'bg-red-100 text-red-700 border-red-200',
                'paid'          => 'bg-purple-100 text-purple-700 border-purple-200',
                'permit_issued' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
            ];
        @endphp
        <span class="px-4 py-1.5 rounded-full text-sm font-semibold border
                     {{ $statusClasses[$application->status] ?? 'bg-slate-100 text-slate-600' }}">
            {{ ucfirst(str_replace('_', ' ', $application->status)) }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT: Application Details --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Basic Info Card --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900 mb-4 pb-2 border-b border-slate-100">
                    <i class="bi bi-building mr-2 text-blue-500"></i>Business Information
                </h2>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Business Name</p>
                        <p class="text-slate-900 font-medium mt-0.5">{{ $application->business_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Trade Name</p>
                        <p class="text-slate-900 font-medium mt-0.5">{{ $application->trade_name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Transaction Type</p>
                        <p class="text-slate-900 font-medium mt-0.5">{{ $application->transact_type }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Business Type</p>
                        <p class="text-slate-900 font-medium mt-0.5">{{ $application->business_info }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">DTI/SEC No.</p>
                        <p class="text-slate-900 font-medium mt-0.5 font-mono">{{ $application->reg_num }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">TIN</p>
                        <p class="text-slate-900 font-medium mt-0.5 font-mono">{{ $application->business_tin }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Business Activity</p>
                        <p class="text-slate-900 font-medium mt-0.5">{{ $application->business_act }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Billing Frequency</p>
                        <p class="text-slate-900 font-medium mt-0.5">{{ $application->billing_freq }}</p>
                    </div>
                </div>
            </div>

            {{-- Owner Info Card --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900 mb-4 pb-2 border-b border-slate-100">
                    <i class="bi bi-person mr-2 text-blue-500"></i>Owner / Representative
                </h2>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    @if($application->business_info === 'Sole Proprietorship')
                        <div>
                            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Last Name</p>
                            <p class="text-slate-900 font-medium mt-0.5">{{ $application->sp_owner_lname ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">First Name</p>
                            <p class="text-slate-900 font-medium mt-0.5">{{ $application->sp_owner_fname ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Middle Name</p>
                            <p class="text-slate-900 font-medium mt-0.5">{{ $application->sp_owner_mname ?? '—' }}</p>
                        </div>
                    @else
                        <div>
                            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Last Name</p>
                            <p class="text-slate-900 font-medium mt-0.5">{{ $application->corp_owner_lname ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">First Name</p>
                            <p class="text-slate-900 font-medium mt-0.5">{{ $application->corp_owner_fname ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Middle Name</p>
                            <p class="text-slate-900 font-medium mt-0.5">{{ $application->corp_owner_mname ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Corporation Type</p>
                            <p class="text-slate-900 font-medium mt-0.5">{{ $application->corp_location ?? '—' }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Address Card --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900 mb-4 pb-2 border-b border-slate-100">
                    <i class="bi bi-geo-alt mr-2 text-blue-500"></i>Business Address
                </h2>
                <div class="grid grid-cols-3 gap-3 text-sm">
                    @foreach([
                        'House/Bldg No.' => $application->house_num,
                        'Building Name'  => $application->building_name,
                        'Lot No.'        => $application->lot_num,
                        'Block No.'      => $application->block_num,
                        'Street'         => $application->street,
                        'Barangay'       => $application->barangay,
                        'Subdivision'    => $application->subdivision,
                        'City/Municipality' => $application->city_muni,
                        'Province'       => $application->province,
                        'Zip Code'       => $application->zip_code,
                    ] as $label => $value)
                        <div>
                            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">{{ $label }}</p>
                            <p class="text-slate-900 font-medium mt-0.5">{{ $value ?? '—' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Uploaded Documents --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900 mb-4 pb-2 border-b border-slate-100">
                    <i class="bi bi-paperclip mr-2 text-blue-500"></i>Submitted Documents
                </h2>

                @php
                    $docLabels = [
                        'dti_sec_certificate' => 'DTI / SEC Certificate',
                        'valid_id'            => 'Valid ID (with 3 Signatures)',
                        'business_photo'      => 'Photo of Business',
                        'business_sketch'     => 'Business Sketch / Location Map',
                    ];
                    $docIcons = [
                        'dti_sec_certificate' => 'bi-file-earmark-text',
                        'valid_id'            => 'bi-person-badge',
                        'business_photo'      => 'bi-camera',
                        'business_sketch'     => 'bi-map',
                    ];
                @endphp

                <div class="space-y-3">
                    @forelse($application->documents as $doc)
                        <div class="flex items-center justify-between p-3 rounded-xl
                                    border border-slate-100 bg-slate-50 hover:bg-slate-100 transition">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center
                                            justify-center text-blue-600">
                                    <i class="bi {{ $docIcons[$doc->document_type] ?? 'bi-file-earmark' }} text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">
                                        {{ $docLabels[$doc->document_type] ?? $doc->document_type }}
                                    </p>
                                    <p class="text-xs text-slate-400">
                                        {{ $doc->file_name }} · {{ number_format($doc->file_size / 1024, 1) }} KB
                                    </p>
                                </div>
                            </div>
                            {{-- View Document button --}}
                            {{-- In production you'd create a signed URL. For now a simple route. --}}
                            <span class="text-xs px-3 py-1.5 rounded-lg bg-blue-600 text-white font-medium">
                                <i class="bi bi-eye mr-1"></i>View
                            </span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400 text-center py-4">No documents found.</p>
                    @endforelse
                </div>
            </div>

            {{-- test --}}
            


            {{-- Payment Info (if paid) --}}
            @if($application->payment)
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                    <h2 class="text-base font-semibold text-slate-900 mb-4 pb-2 border-b border-slate-100">
                        <i class="bi bi-credit-card mr-2 text-green-500"></i>Payment Information
                    </h2>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">
                                Payment Method
                            </p>
                            <p class="text-slate-900 font-medium mt-0.5 capitalize">
                                {{ str_replace('_', ' ', $application->payment->payment_method) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">
                                Reference No.
                            </p>
                            <p class="text-slate-900 font-medium mt-0.5 font-mono">
                                {{ $application->payment->reference_number }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Amount</p>
                            <p class="text-slate-900 font-bold mt-0.5 text-lg">
                                ₱{{ number_format($application->payment->amount, 2) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">
                                Payment Status
                            </p>
                            <span class="inline-block mt-0.5 px-2.5 py-0.5 rounded-full text-xs font-semibold
                                         {{ $application->payment->status === 'verified'
                                            ? 'bg-green-100 text-green-700'
                                            : 'bg-yellow-100 text-yellow-700' }}">
                                {{ ucfirst($application->payment->status) }}
                            </span>
                        </div>
                    </div>

                    {{-- Verify payment button --}}
                    @if($application->payment->status === 'pending')
                        <form method="POST"
                              action="{{ route('employee.manager.payments.verify', $application->payment->id) }}"
                              class="mt-4">
                            @csrf
                            <button type="submit"
                                    class="w-full py-2.5 bg-green-600 hover:bg-green-700 text-white
                                           text-sm font-semibold rounded-lg transition-colors">
                                <i class="bi bi-check-circle mr-1"></i>
                                Verify Payment & Mark as Paid
                            </button>
                        </form>
                    @endif
                </div>
            @endif

        </div>

        {{-- RIGHT: Action Panel --}}
        <div class="space-y-4">

            {{-- Applicant Info Card --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Applicant</h3>
                <div class="flex items-center gap-3 mb-3">
                    <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center
                                justify-center text-white font-bold text-sm flex-shrink-0">
                        {{ strtoupper(substr($application->user->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-900 truncate">
                            {{ $application->user->name }}
                        </p>
                        <p class="text-xs text-slate-400 truncate">{{ $application->user->email }}</p>
                    </div>
                </div>
                <p class="text-xs text-slate-500">
                    <i class="bi bi-calendar3 mr-1"></i>
                    Applied {{ $application->created_at->format('M d, Y') }}
                </p>
            </div>

            {{-- ── APPROVE PANEL ─────────────────────────── --}}
            @if(in_array($application->status, ['pending', 'under_review']))
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                    <h3 class="text-sm font-semibold text-slate-700 mb-3">Approve Application</h3>
                    <form method="POST"
                          action="{{ route('employee.manager.applications.approve', $application->id) }}"
                          class="space-y-3">
                        @csrf
                        <div>
                            <label for="permit_fee"
                                   class="block text-xs font-semibold text-slate-600 mb-1">
                                Set Permit Fee (₱) <span class="text-red-500">*</span>
                            </label>
                            <input id="permit_fee"
                                   type="number"
                                   name="permit_fee"
                                   step="0.01"
                                   min="1"
                                   placeholder="e.g. 1500.00"
                                   class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300
                                          focus:outline-none focus:ring-2 focus:ring-green-500
                                          focus:border-green-500 transition" />
                            @error('permit_fee')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit"
                                onclick="return confirm('Approve this application?')"
                                class="w-full py-2.5 bg-green-600 hover:bg-green-700 text-white
                                       text-sm font-semibold rounded-lg transition-colors">
                            <i class="bi bi-check-circle mr-1"></i>
                            Approve Application
                        </button>
                    </form>
                </div>

                {{-- ── REJECT PANEL ──────────────────────── --}}
                <div class="bg-white rounded-2xl border border-red-100 p-5 shadow-sm">
                    <h3 class="text-sm font-semibold text-red-700 mb-3">Reject Application</h3>
                    <form method="POST"
                          action="{{ route('employee.manager.applications.reject', $application->id) }}"
                          class="space-y-3">
                        @csrf
                        <div>
                            <label for="rejection_reason"
                                   class="block text-xs font-semibold text-slate-600 mb-1">
                                Reason for Rejection <span class="text-red-500">*</span>
                            </label>
                            <textarea id="rejection_reason"
                                      name="rejection_reason"
                                      rows="4"
                                      placeholder="Explain why this application is being rejected..."
                                      class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300
                                             resize-none focus:outline-none focus:ring-2
                                             focus:ring-red-500 focus:border-red-500 transition">{{ old('rejection_reason') }}</textarea>
                            @error('rejection_reason')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit"
                                onclick="return confirm('Reject this application? This cannot be undone.')"
                                class="w-full py-2.5 bg-red-600 hover:bg-red-700 text-white
                                       text-sm font-semibold rounded-lg transition-colors">
                            <i class="bi bi-x-circle mr-1"></i>
                            Reject Application
                        </button>
                    </form>
                </div>
            @endif

            {{-- ── ISSUE PERMIT (if paid and verified) ──── --}}
            @if($application->status === 'paid' && $application->payment?->status === 'verified')
                <div class="bg-white rounded-2xl border border-emerald-200 p-5 shadow-sm">
                    <h3 class="text-sm font-semibold text-emerald-700 mb-3">
                        <i class="bi bi-award mr-1"></i>Issue Permit
                    </h3>
                    <p class="text-xs text-slate-500 mb-3">
                        Payment has been verified. You can now officially issue the business permit.
                    </p>
                    <form method="POST"
                          action="{{ route('employee.manager.applications.issue', $application->id) }}">
                        @csrf
                        <button type="submit"
                                onclick="return confirm('Issue the permit for this application?')"
                                class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white
                                       text-sm font-semibold rounded-lg transition-colors">
                            <i class="bi bi-award mr-1"></i>
                            Issue Business Permit
                        </button>
                    </form>
                </div>
            @endif

            {{-- Rejection Reason (if rejected) --}}
            @if($application->status === 'rejected' && $application->rejection_reason)
                <div class="bg-red-50 border border-red-200 rounded-2xl p-5">
                    <h3 class="text-sm font-semibold text-red-700 mb-2">Rejection Reason</h3>
                    <p class="text-sm text-red-600">{{ $application->rejection_reason }}</p>
                </div>
            @endif

        </div>
    </div>

</div>
@endsection