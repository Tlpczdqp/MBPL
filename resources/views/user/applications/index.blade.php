@extends('layouts.app')
@section('title', 'My Applications')

@section('content')
<div class="max-w-6xl mx-auto">

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">My Business Applications</h1>
            <p class="text-sm text-slate-500 mt-1">Track the status of all your applications</p>
        </div>
        <a href="{{ route('user.business.create', ['userId' => auth()->id()]) }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 hover:bg-slate-700
                  text-white text-sm font-semibold rounded-lg transition-colors">
            <i class="bi bi-plus-lg"></i>
            New Application
        </a>
    </div>

    {{-- Applications Table --}}
    @if($applications->isEmpty())
        {{-- Empty state --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-16 text-center shadow-sm">
            <i class="bi bi-folder-x text-5xl text-slate-300 block mb-3"></i>
            <h3 class="text-lg font-semibold text-slate-700">No applications yet</h3>
            <p class="text-sm text-slate-400 mt-1 mb-6">
                Start by applying for your first business permit.
            </p>
            <a href="{{ route('user.business.create', ['userId' => auth()->id()]) }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700
                      text-white text-sm font-semibold rounded-lg transition-colors">
                <i class="bi bi-file-earmark-plus"></i>
                Apply Now
            </a>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Application #
                            </th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Business Name
                            </th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Type
                            </th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Date Submitted
                            </th>
                            <th class="text-right px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($applications as $app)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-4 font-mono text-xs text-slate-600">
                                {{ $app->application_number }}
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-semibold text-slate-900">{{ $app->business_name }}</p>
                                <p class="text-xs text-slate-400">{{ $app->business_info }}</p>
                            </td>
                            <td class="px-5 py-4 text-slate-600">
                                {{ $app->transact_type }}
                            </td>
                            <td class="px-5 py-4">
                                {{-- Status badge: different colors per status --}}
                                @php
                                    $statusClasses = [
                                        'pending'       => 'bg-yellow-100 text-yellow-700',
                                        'under_review'  => 'bg-blue-100 text-blue-700',
                                        'approved'      => 'bg-green-100 text-green-700',
                                        'rejected'      => 'bg-red-100 text-red-700',
                                        'paid'          => 'bg-purple-100 text-purple-700',
                                        'permit_issued' => 'bg-emerald-100 text-emerald-700',
                                    ];
                                    $statusLabels = [
                                        'pending'       => 'Pending',
                                        'under_review'  => 'Under Review',
                                        'approved'      => 'Approved',
                                        'rejected'      => 'Rejected',
                                        'paid'          => 'Payment Submitted',
                                        'permit_issued' => 'Permit Issued ✓',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                             {{ $statusClasses[$app->status] ?? 'bg-slate-100 text-slate-600' }}">
                                    {{ $statusLabels[$app->status] ?? $app->status }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-slate-500 text-xs">
                                {{ $app->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- View button --}}
                                    <a href="{{ route('user.business.show', ['userId' => auth()->id(), 'application' => $app->id]) }}"
                                       class="text-xs px-3 py-1.5 rounded-lg border border-slate-200
                                              text-slate-600 hover:bg-slate-100 transition-colors">
                                        View
                                    </a>

                                    {{-- Pay button (only if approved) --}}
                                    @if($app->status === 'approved')
                                        <a href="{{ route('user.payment.show', ['userId' => auth()->id(), 'application' => $app->id]) }}"
                                           class="text-xs px-3 py-1.5 rounded-lg bg-green-600 hover:bg-green-700
                                                  text-white font-semibold transition-colors">
                                            Pay Now
                                        </a>
                                    @endif

                                    {{-- Print button (only if permit issued) --}}
                                    @if($app->status === 'permit_issued')
                                        <a href="{{ route('user.permit.print', ['userId' => auth()->id(), 'application' => $app->id]) }}"
                                           target="_blank"
                                           class="text-xs px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700
                                                  text-white font-semibold transition-colors">
                                            <i class="bi bi-printer mr-1"></i>Print
                                        </a>

                                        {{-- Renew button --}}
                                        <a href="{{ route('user.business.renew', ['userId' => auth()->id(), 'application' => $app->id]) }}"
                                           class="text-xs px-3 py-1.5 rounded-lg bg-amber-500 hover:bg-amber-600
                                                  text-white font-semibold transition-colors">
                                            Renew
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($applications->hasPages())
                <div class="px-5 py-4 border-t border-slate-100">
                    {{ $applications->links() }}
                </div>
            @endif
        </div>
    @endif

</div>
@endsection