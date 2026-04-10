@extends('layouts.employee')
@section('title', 'Audit Logs')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Audit Logs</h1>
            <p class="text-sm text-slate-500 mt-1">
                Complete activity history across all applications
            </p>
        </div>
        {{-- Admin badge --}}
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg
                     bg-red-100 text-red-700 text-xs font-semibold">
            <i class="bi bi-shield-lock"></i>
            Admin Only
        </span>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-xl border border-slate-200 p-4 mb-6 shadow-sm">
        <form method="GET" class="flex flex-wrap items-end gap-3">

            {{-- Event Filter --}}
            <div>
                <label class="text-xs font-semibold text-slate-500 uppercase mb-1 block">
                    Event
                </label>
                <select name="event"
                        class="text-sm border border-slate-200 rounded-lg px-3 py-2
                               text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Events</option>
                    <option value="created"  {{ request('event') === 'created'  ? 'selected' : '' }}>Created</option>
                    <option value="updated"  {{ request('event') === 'updated'  ? 'selected' : '' }}>Updated</option>
                    <option value="deleted"  {{ request('event') === 'deleted'  ? 'selected' : '' }}>Deleted</option>
                    <option value="restored" {{ request('event') === 'restored' ? 'selected' : '' }}>Restored</option>
                </select>
            </div>

            {{-- Date Filter --}}
            <div>
                <label class="text-xs font-semibold text-slate-500 uppercase mb-1 block">
                    Date
                </label>
                <input type="date" name="date" value="{{ request('date') }}"
                       class="text-sm border border-slate-200 rounded-lg px-3 py-2
                              text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            {{-- Buttons --}}
            <button type="submit"
                    class="px-4 py-2 bg-slate-900 hover:bg-slate-700 text-white
                           text-sm font-semibold rounded-lg transition-colors">
                <i class="bi bi-funnel mr-1"></i> Filter
            </button>
            <a href="{{ route('employee.admin.audit.index') }}"
               class="px-4 py-2 border border-slate-200 hover:bg-slate-100
                      text-slate-600 text-sm font-semibold rounded-lg transition-colors">
                <i class="bi bi-x-lg mr-1"></i> Clear
            </a>
        </form>
    </div>

    {{-- Audit Table --}}
    @if($audits->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 p-16 text-center shadow-sm">
            <i class="bi bi-clock-history text-5xl text-slate-300 block mb-3"></i>
            <h3 class="text-lg font-semibold text-slate-700">No audit logs found</h3>
            <p class="text-sm text-slate-400 mt-1">Activity will appear here as changes are made.</p>
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
                                Model
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
                            {{-- <th class="text-right px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Actions
                            </th> --}}
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($audits as $audit)
                        <tr class="hover:bg-slate-50 transition-colors">

                            {{-- Event Badge --}}
                            <td class="px-5 py-4">
                                @php
                                    $eventClasses = [
                                        'created'  => 'bg-green-100 text-green-700',
                                        'updated'  => 'bg-blue-100 text-blue-700',
                                        'deleted'  => 'bg-red-100 text-red-700',
                                        'restored' => 'bg-amber-100 text-amber-700',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full
                                             text-xs font-medium
                                             {{ $eventClasses[$audit->event] ?? 'bg-slate-100 text-slate-600' }}">
                                    {{ ucfirst($audit->event) }}
                                </span>
                            </td>

                            {{-- Model --}}
                            <td class="px-5 py-4">
                                <p class="font-semibold text-slate-900 text-xs">
                                    {{ class_basename($audit->auditable_type) }}
                                </p>
                                <p class="text-xs text-slate-400">ID: {{ $audit->auditable_id }}</p>
                            </td>

                            {{-- Changed By --}}
                            <td class="px-5 py-4">
                                @if($audit->user)
                                    <p class="font-semibold text-slate-900">{{ $audit->user->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $audit->user->email }}</p>
                                @else
                                    <span class="text-xs text-slate-400 italic">System</span>
                                @endif
                            </td>

                            {{-- Old Values --}}
                            <td class="px-5 py-4 max-w-[160px]">
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
                            <td class="px-5 py-4 max-w-[160px]">
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
                                {{ $audit->created_at->format('M d, Y') }}<br>
                                <span class="text-slate-400">{{ $audit->created_at->format('h:i A') }}</span>
                            </td>

                            {{-- View Application --}}
                            {{-- <td class="px-5 py-4 text-right">
                                @if($audit->auditable)
                                    <a href="{{ route('employee.admin.audit.show', $audit->auditable_id) }}"
                                       class="text-xs px-3 py-1.5 rounded-lg border border-slate-200
                                              text-slate-600 hover:bg-slate-100 transition-colors">
                                        View Logs
                                    </a>
                                @endif
                            </td> --}}

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