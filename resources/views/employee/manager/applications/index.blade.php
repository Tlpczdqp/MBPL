{{-- resources/views/employee/manager/applications/index.blade.php --}}
@extends('layouts.employee')

@section('title', 'All Applications')

@section('content')
 @php
            $filters = [
                ['label' => 'All',           'value' => 'all',          'icon' => 'bi-stack',          'color' => 'slate',   'count' => $counts['all']],
                ['label' => 'Pending',       'value' => 'pending',      'icon' => 'bi-clock',          'color' => 'yellow',  'count' => $counts['pending']],
                ['label' => 'Under Review',  'value' => 'under_review', 'icon' => 'bi-search',         'color' => 'blue',    'count' => $counts['under_review']],
                ['label' => 'Approved',      'value' => 'approved',     'icon' => 'bi-check-circle',   'color' => 'green',   'count' => $counts['approved']],
                ['label' => 'Paid',          'value' => 'paid',         'icon' => 'bi-credit-card',    'color' => 'purple',  'count' => $counts['paid']],
                ['label' => 'Rejected',      'value' => 'rejected',     'icon' => 'bi-x-circle',       'color' => 'red',     'count' => $counts['rejected']],
                ['label' => 'Permit Issued', 'value' => 'permit_issued','icon' => 'bi-award',          'color' => 'emerald', 'count' => $counts['permit_issued']],
            ];
            $activeStatus = request('status', 'all');

            // $colorMap = [
            //     'slate'   => ['ring' => 'ring-slate-400',   'border' => 'border-slate-300',  'icon' => 'text-slate-500',   'dot' => 'bg-slate-500'],
            //     'yellow'  => ['ring' => 'ring-yellow-400',  'border' => 'border-yellow-300', 'icon' => 'text-yellow-500',  'dot' => 'bg-yellow-500'],
            //     'blue'    => ['ring' => 'ring-blue-400',    'border' => 'border-blue-300',   'icon' => 'text-blue-500',    'dot' => 'bg-blue-500'],
            //     'green'   => ['ring' => 'ring-green-400',   'border' => 'border-green-300',  'icon' => 'text-green-500',   'dot' => 'bg-green-500'],
            //     'purple'  => ['ring' => 'ring-purple-400',  'border' => 'border-purple-300', 'icon' => 'text-purple-500',  'dot' => 'bg-purple-500'],
            //     'red'     => ['ring' => 'ring-red-400',     'border' => 'border-red-300',    'icon' => 'text-red-500',     'dot' => 'bg-red-500'],
            //     'emerald' => ['ring' => 'ring-emerald-400', 'border' => 'border-emerald-300','icon' => 'text-emerald-500', 'dot' => 'bg-emerald-500'],
            // ];
        @endphp
<div>
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">All Applications</h1>
            <p class="text-sm text-slate-500 mt-1">Review and manage all submitted business permit applications</p>
        </div>
        <div class="mt-3 sm:mt-0">
            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500
                         bg-slate-100 px-3 py-1.5 rounded-lg">
                <i class="bi bi-stack"></i>
                {{ $applications->total() }} Total Applications
            </span>
        </div>
    </div>

    {{-- ── Status Filter Cards ───────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-3 mb-6">
       

        {{-- @foreach($filters as $filter)
            @php $c = $colorMap[$filter['color']]; @endphp
            <a href="{{ route('employee.manager.applications.index', array_merge(request()->except('status', 'page'), ['status' => $filter['value']])) }}"
               class="bg-white rounded-xl border p-3 shadow-sm hover:shadow-md transition group
                      {{ $activeStatus === $filter['value']
                            ? 'ring-2 ' . $c['ring'] . ' ' . $c['border']
                            : 'border-slate-200 hover:border-slate-300' }}">
                <div class="flex items-center justify-between mb-2">
                    <i class="bi {{ $filter['icon'] }} {{ $c['icon'] }} text-base"></i>
                    @if($activeStatus === $filter['value'])
                        <span class="w-2 h-2 rounded-full {{ $c['dot'] }} animate-pulse"></span>
                    @endif
                </div>
                <p class="text-xl font-bold text-slate-900">{{ $filter['count'] }}</p>
                <p class="text-xs text-slate-500 font-medium mt-0.5 leading-tight">{{ $filter['label'] }}</p>
            </a>
        @endforeach --}}
    </div>

    {{-- ── Search & Sort Bar ────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm mb-6">
        <form method="GET"
              action="{{ route('employee.manager.applications.index') }}"
              class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">

            {{-- Preserve active status filter --}}
            <input type="hidden" name="status" value="{{ $activeStatus }}">

            {{-- Search --}}
            <div class="relative flex-1">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search by app #, business name, or applicant..."
                       class="w-full pl-9 pr-4 py-2.5 text-sm rounded-lg border border-slate-300
                              focus:outline-none focus:ring-2 focus:ring-blue-500
                              focus:border-blue-500 transition" />
            </div>

            {{-- Sort --}}
            <select name="sort"
                    class="px-3 py-2.5 text-sm rounded-lg border border-slate-300 bg-white
                           focus:outline-none focus:ring-2 focus:ring-blue-500
                           focus:border-blue-500 transition">
                <option value="newest"        {{ request('sort', 'newest') === 'newest'        ? 'selected' : '' }}>Newest First</option>
                <option value="oldest"        {{ request('sort') === 'oldest'                  ? 'selected' : '' }}>Oldest First</option>
                <option value="business_name" {{ request('sort') === 'business_name'           ? 'selected' : '' }}>Business Name A–Z</option>
            </select>
            <select name="status"
                class="px-3 py-2.5 text-sm rounded-lg border border-slate-300 bg-white
                       focus:outline-none focus:ring-2 focus:ring-blue-500
                       focus:border-blue-500 transition">
            <option value="all"          {{ request('status', 'all') === 'all'          ? 'selected' : '' }}>All Status</option>
            <option value="pending"      {{ request('status') === 'pending'             ? 'selected' : '' }}>Pending</option>
            <option value="under_review" {{ request('status') === 'under_review'        ? 'selected' : '' }}>Under Review</option>
            <option value="approved"     {{ request('status') === 'approved'            ? 'selected' : '' }}>Approved</option>
            <option value="paid"         {{ request('status') === 'paid'               ? 'selected' : '' }}>Payment Submitted</option>
            <option value="rejected"     {{ request('status') === 'rejected'           ? 'selected' : '' }}>Rejected</option>
            <option value="permit_issued"{{ request('status') === 'permit_issued'      ? 'selected' : '' }}>Permit Issued</option>
        </select>

            {{-- Search Button --}}
            <button type="submit"
                    class="px-5 py-2.5 bg-slate-900 hover:bg-slate-700 text-white
                           text-sm font-semibold rounded-lg transition-colors">
                <i class="bi bi-search mr-1"></i> Search
            </button>

            {{-- Reset --}}
            @if(request('search') || $activeStatus !== 'all' || request('sort'))
                <a href="{{ route('employee.manager.applications.index') }}"
                   class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5
                          text-sm text-slate-500 hover:text-slate-700 hover:bg-slate-100
                          rounded-lg transition">
                    <i class="bi bi-x-lg"></i> Reset
                </a>
            @endif
        </form>
    </div>

    {{-- ── Applications Table ───────────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">

        {{-- Table Toolbar --}}
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                <i class="bi bi-file-earmark-text text-slate-400"></i>
                @if($activeStatus === 'all')
                    All Applications
                @else
                    {{ collect($filters)->firstWhere('value', $activeStatus)['label'] ?? ucfirst($activeStatus) }} Applications
                @endif
            </h3>
            <span class="text-xs font-medium text-slate-500 bg-slate-200 px-2.5 py-1 rounded-full">
                {{ $applications->total() }} result(s)
            </span>
        </div>

        @if($applications->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/50">
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Application #
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Business Name
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Applicant
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Type
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Date Submitted
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($applications as $app)
                            @php
                                $statusConfig = [
                                    'pending'       => ['label' => 'Pending',           'bg' => 'bg-yellow-100',  'text' => 'text-yellow-700'],
                                    'under_review'  => ['label' => 'Under Review',      'bg' => 'bg-blue-100',    'text' => 'text-blue-700'],
                                    'approved'      => ['label' => 'Approved',          'bg' => 'bg-green-100',   'text' => 'text-green-700'],
                                    'rejected'      => ['label' => 'Rejected',          'bg' => 'bg-red-100',     'text' => 'text-red-700'],
                                    'paid'          => ['label' => 'Payment Submitted', 'bg' => 'bg-purple-100',  'text' => 'text-purple-700'],
                                    'permit_issued' => ['label' => 'Permit Issued ✓',   'bg' => 'bg-emerald-100', 'text' => 'text-emerald-700'],
                                ];
                                $s = $statusConfig[$app->status] ?? [
                                    'label' => ucfirst($app->status),
                                    'bg'    => 'bg-slate-100',
                                    'text'  => 'text-slate-700'
                                ];
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition">

                                {{-- Application Number --}}
                                <td class="px-6 py-4">
                                    <span class="font-mono text-sm font-medium text-slate-800">
                                        {{ $app->application_number }}
                                    </span>
                                </td>

                                {{-- Business Name --}}
                                <td class="px-6 py-4">
                                    <p class="text-sm font-semibold text-slate-800">{{ $app->business_name }}</p>
                                    <p class="text-xs text-slate-400">{{ $app->business_info }}</p>
                                </td>

                                {{-- Applicant --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-blue-600 flex items-center
                                                    justify-center flex-shrink-0">
                                            <span class="text-xs font-bold text-white">
                                                {{ strtoupper(substr($app->user->name ?? '?', 0, 1)) }}
                                            </span>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-slate-700 truncate">
                                                {{ $app->user->name ?? 'Unknown' }}
                                            </p>
                                            <p class="text-xs text-slate-400 truncate">
                                                {{ $app->user->email ?? '—' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Transaction Type --}}
                                <td class="px-6 py-4">
                                    <span class="text-xs font-medium text-slate-600 bg-slate-100
                                                 px-2 py-1 rounded-md">
                                        {{ $app->transact_type }}
                                    </span>
                                </td>

                                {{-- Status Badge --}}
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full
                                                 text-xs font-semibold {{ $s['bg'] }} {{ $s['text'] }}">
                                        {{ $s['label'] }}
                                    </span>
                                </td>

                                {{-- Date Submitted --}}
                                <td class="px-6 py-4">
                                    <p class="text-sm text-slate-600">{{ $app->created_at->format('M d, Y') }}</p>
                                    <p class="text-xs text-slate-400">{{ $app->created_at->format('h:i A') }}</p>
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">

                                        {{-- View --}}
                                        <a href="{{ route('employee.manager.applications.show', $app->id) }}"
                                           class="inline-flex items-center gap-1.5 rounded-lg border
                                                  border-slate-300 bg-white px-3 py-1.5 text-xs font-medium
                                                  text-slate-700 transition hover:bg-slate-50">
                                            <i class="bi bi-eye"></i> View
                                        </a>

                                        {{-- Review (pending / under_review) --}}
                                        @if(in_array($app->status, ['pending', 'under_review']))
                                            <a href="{{ route('employee.manager.applications.show', $app->id) }}"
                                               class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600
                                                      hover:bg-blue-700 px-3 py-1.5 text-xs font-semibold
                                                      text-white transition">
                                                <i class="bi bi-clipboard-check"></i> Review
                                            </a>
                                        @endif

                                        {{-- Verify Payment (paid) --}}
                                        @if($app->status === 'paid')
                                            <a href="{{ route('employee.manager.applications.show', $app->id) }}"
                                               class="inline-flex items-center gap-1.5 rounded-lg bg-purple-600
                                                      hover:bg-purple-700 px-3 py-1.5 text-xs font-semibold
                                                      text-white transition">
                                                <i class="bi bi-credit-card-2-front"></i> Verify
                                            </a>
                                        @endif

                                        {{-- Issue Permit --}}
                                        @if($app->status === 'paid' && $app->payment?->status === 'verified')
                                            <a href="{{ route('employee.manager.applications.show', $app->id) }}"
                                               class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600
                                                      hover:bg-emerald-700 px-3 py-1.5 text-xs font-semibold
                                                      text-white transition">
                                                <i class="bi bi-award"></i> Issue
                                            </a>
                                        @endif

                                        {{-- Print Permit --}}
                                        {{-- @if($app->status === 'permit_issued')
                                            <a href="{{ route('employee.manager.permit.print', $app->id) }}"
                                               target="_blank"
                                               class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600
                                                      hover:bg-emerald-700 px-3 py-1.5 text-xs font-semibold
                                                      text-white transition">
                                                <i class="bi bi-printer"></i> Print
                                            </a>
                                        @endif --}}

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($applications->hasPages())
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $applications->appends(request()->query())->links() }}
                </div>
            @endif

        @else
            {{-- Empty State --}}
            <div class="text-center py-16">
                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                    <i class="bi bi-inbox text-3xl text-slate-300"></i>
                </div>
                <p class="text-sm font-medium text-slate-500">
                    @if(request('search') || $activeStatus !== 'all')
                        No applications match your search or filter.
                    @else
                        No applications found.
                    @endif
                </p>
                <p class="text-xs text-slate-400 mt-1">
                    @if(request('search') || $activeStatus !== 'all')
                        Try adjusting your search or clearing the filters.
                    @else
                        Applications submitted by users will appear here.
                    @endif
                </p>
                @if(request('search') || $activeStatus !== 'all')
                    <a href="{{ route('employee.manager.applications.index') }}"
                       class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-slate-900
                              hover:bg-slate-700 text-white text-sm font-semibold rounded-lg transition">
                        <i class="bi bi-arrow-counterclockwise"></i> Clear Filters
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection