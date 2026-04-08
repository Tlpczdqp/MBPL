{{-- resources/views/employee/staff/applications/index.blade.php --}}
@extends('layouts.employee')

@section('title', 'My Applications')

@section('content')
<div>
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Applications</h1>
            <p class="text-sm text-slate-500 mt-1">Review and process pending business permit applications</p>
        </div>
    </div>

    {{-- Applications Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">

        {{-- Table Header --}}
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-700">
                    <i class="bi bi-file-earmark-text text-slate-400 mr-1"></i>
                    Pending & Under Review
                </h3>
                <span class="text-xs font-medium text-slate-500 bg-slate-200 px-2.5 py-1 rounded-full">
                    {{ $applications->total() }} application(s)
                </span>
            </div>
        </div>

        @if($applications->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/50">
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Application #</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Business Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Applicant</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Date Submitted</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($applications as $app)
                        @php
                            $statusConfig = [
                                'pending'      => ['label' => 'Pending',      'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'],
                                'under_review' => ['label' => 'Under Review', 'bg' => 'bg-blue-100',   'text' => 'text-blue-700'],
                            ];
                            $s = $statusConfig[$app->status] ?? ['label' => ucfirst($app->status), 'bg' => 'bg-slate-100', 'text' => 'text-slate-700'];
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4">
                                <span class="text-sm font-mono font-medium text-slate-800">{{ $app->application_number }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">{{ $app->business_name }}</p>
                                    <p class="text-xs text-slate-400">{{ $app->business_info }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-slate-200 flex items-center justify-center">
                                        <span class="text-xs font-bold text-slate-600">
                                            {{ strtoupper(substr($app->user->name ?? '?', 0, 1)) }}
                                        </span>
                                    </div>
                                    <span class="text-sm text-slate-700">{{ $app->user->name ?? 'Unknown' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-slate-600">{{ $app->transact_type }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $s['bg'] }} {{ $s['text'] }}">
                                    {{ $s['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-slate-500">{{ $app->created_at->format('M d, Y') }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('employee.staff.applications.show', $app->id) }}"
                                   class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-50">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($applications->hasPages())
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $applications->links() }}
                </div>
            @endif

        @else
            <div class="text-center py-16">
                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                    <i class="bi bi-inbox text-3xl text-slate-300"></i>
                </div>
                <p class="text-sm font-medium text-slate-500">No pending applications</p>
                <p class="text-xs text-slate-400 mt-1">New applications will appear here</p>
            </div>
        @endif
    </div>
</div>
@endsection