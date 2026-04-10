{{-- resources/views/employee/audits/show.blade.php --}}

@extends('layouts.employee')
@section('title', 'Audit Log')

@section('content')
<div class="max-w-6xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Audit Log</h1>
            <p class="text-sm text-slate-500 mt-1">
                Activity history for {{ $application->business_name }}
            </p>
        </div>
        <a href="{{ url()->previous() }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 hover:bg-slate-700
                  text-white text-sm font-semibold rounded-lg transition-colors">
            <i class="bi bi-arrow-left"></i>
            Back
        </a>
    </div>

    @if($audits->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 p-16 text-center shadow-sm">
            <i class="bi bi-clock-history text-5xl text-slate-300 block mb-3"></i>
            <h3 class="text-lg font-semibold text-slate-700">No audit logs yet</h3>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Event
                            </th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Changed By
                            </th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Old Values
                            </th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                New Values
                            </th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                IP Address
                            </th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Date & Time
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($audits as $audit)
                        <tr class="hover:bg-slate-50 transition-colors">

                            {{-- Event Badge --}}
                            <td class="px-5 py-4">
                                @php
                                    $eventClasses = [
                                        'created' => 'bg-green-100 text-green-700',
                                        'updated' => 'bg-blue-100 text-blue-700',
                                        'deactivated' => 'bg-red-100 text-red-700',
                                        'restored' => 'bg-amber-100 text-amber-700',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                             {{ $eventClasses[$audit->event] ?? 'bg-slate-100 text-slate-600' }}">
                                    {{ ucfirst($audit->event) }}
                                </span>
                            </td>

                            {{-- Changed By --}}
                            <td class="px-5 py-4">
                                @if($audit->user)
                                    <p class="font-semibold text-slate-900">{{ $audit->user->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $audit->user->email }}</p>
                                @else
                                    <span class="text-xs text-slate-400">System</span>
                                @endif
                            </td>

                            {{-- Old Values --}}
                            <td class="px-5 py-4">
                                @if(!empty($audit->old_values))
                                    <ul class="space-y-1">
                                        @foreach($audit->old_values as $key => $value)
                                            <li class="text-xs">
                                                <span class="font-semibold text-slate-500">{{ $key }}:</span>
                                                <span class="text-red-600">{{ $value ?? 'null' }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-xs text-slate-300">—</span>
                                @endif
                            </td>

                            {{-- New Values --}}
                            <td class="px-5 py-4">
                                @if(!empty($audit->new_values))
                                    <ul class="space-y-1">
                                        @foreach($audit->new_values as $key => $value)
                                            <li class="text-xs">
                                                <span class="font-semibold text-slate-500">{{ $key }}:</span>
                                                <span class="text-green-600">{{ $value ?? 'null' }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-xs text-slate-300">—</span>
                                @endif
                            </td>

                            {{-- IP Address --}}
                            <td class="px-5 py-4 text-xs text-slate-500 font-mono">
                                {{ $audit->ip_address ?? '—' }}
                            </td>

                            {{-- Date --}}
                            <td class="px-5 py-4 text-xs text-slate-500">
                                {{ $audit->created_at->format('M d, Y h:i A') }}
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($audits->hasPages())
                <div class="px-5 py-4 border-t border-slate-100">
                    {{ $audits->links() }}
                </div>
            @endif
        </div>
    @endif

</div>
@endsection