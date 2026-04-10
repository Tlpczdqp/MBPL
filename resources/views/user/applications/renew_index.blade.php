@extends('layouts.app')
@section('title', 'Renew Business Permit')

@section('content')
<div class="max-w-6xl mx-auto">

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Renew Business Permit</h1>
            <p class="text-sm text-slate-500 mt-1">
                The following permits are eligible for renewal.
            </p>
        </div>
        <a href="{{ route('user.business.index', ['userId' => auth()->id()]) }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 hover:bg-slate-700
                  text-white text-sm font-semibold rounded-lg transition-colors">
            <i class="bi bi-arrow-left"></i>
            Back to Applications
        </a>
    </div>

    {{-- Info Banner --}}
    <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 mb-6">
        <i class="bi bi-info-circle text-amber-500 text-lg mt-0.5"></i>
        <div>
            <p class="text-sm font-semibold text-amber-800">Renewal Eligibility</p>
            <p class="text-xs text-amber-700 mt-0.5">
                Only applications with a <strong>Permit Issued</strong> status are shown here.
                Click <strong>Renew</strong> on any permit to start the renewal process.
            </p>
        </div>
    </div>

    {{-- Applications Table --}}
    @if($applications->isEmpty())
        {{-- Empty State --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-16 text-center shadow-sm">
            <i class="bi bi-arrow-clockwise text-5xl text-slate-300 block mb-3"></i>
            <h3 class="text-lg font-semibold text-slate-700">No permits available for renewal</h3>
            <p class="text-sm text-slate-400 mt-1 mb-6">
                You don't have any issued permits eligible for renewal yet.
            </p>
            <a href="{{ route('user.business.index', ['userId' => auth()->id()]) }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 hover:bg-slate-700
                      text-white text-sm font-semibold rounded-lg transition-colors">
                <i class="bi bi-folder2-open"></i>
                View All Applications
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

                            {{-- Application Number --}}
                            <td class="px-5 py-4 font-mono text-xs text-slate-600">
                                {{ $app->application_number }}
                            </td>

                            {{-- Business Name --}}
                            <td class="px-5 py-4">
                                <p class="font-semibold text-slate-900">{{ $app->business_name }}</p>
                                <p class="text-xs text-slate-400">{{ $app->business_info }}</p>
                            </td>

                            {{-- Type --}}
                            <td class="px-5 py-4 text-slate-600">
                                {{ $app->transact_type }}
                            </td>

                            {{-- Status (always permit_issued here) --}}
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs
                                             font-medium bg-emerald-100 text-emerald-700">
                                    Permit Issued ✓
                                </span>
                            </td>

                            {{-- Date Submitted --}}
                            <td class="px-5 py-4 text-slate-500 text-xs">
                                {{ $app->created_at->format('M d, Y') }}
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">

                                    {{-- View --}}
                                    <a href="{{ route('user.business.show', ['userId' => auth()->id(), 'application' => $app->id]) }}"
                                       class="text-xs px-3 py-1.5 rounded-lg border border-slate-200
                                              text-slate-600 hover:bg-slate-100 transition-colors">
                                        View
                                    </a>

                                    {{-- Print Permit --}}
                                    <a href="{{ route('user.permit.print', ['userId' => auth()->id(), 'application' => $app->id]) }}"
                                       target="_blank"
                                       class="text-xs px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700
                                              text-white font-semibold transition-colors">
                                        <i class="bi bi-printer mr-1"></i>Print
                                    </a>

                                    {{-- Renew --}}
                                    <a href="{{ route('user.business.renew', ['userId' => auth()->id(), 'application' => $app->id]) }}"
                                       class="text-xs px-3 py-1.5 rounded-lg bg-amber-500 hover:bg-amber-600
                                              text-white font-semibold transition-colors">
                                        <i class="bi bi-arrow-clockwise mr-1"></i>Renew
                                    </a>

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