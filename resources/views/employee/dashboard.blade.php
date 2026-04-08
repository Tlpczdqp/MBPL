@extends('layouts.employee')
@section('title', 'Employee Dashboard')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- Welcome Banner --}}
    {{-- role_color and role_label use the accessor methods from Employee model --}}
    <div class="rounded-2xl p-6 text-white shadow-sm
                {{ $employee->isAdmin()
                    ? 'bg-gradient-to-r from-slate-900 to-red-900'
                    : ($employee->isManager()
                        ? 'bg-gradient-to-r from-slate-900 to-yellow-800'
                        : 'bg-gradient-to-r from-slate-900 to-green-900') }}">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-400 text-sm mb-1">Welcome back,</p>
                <h1 class="text-2xl font-bold">{{ $employee->name }}</h1>
                <div class="flex items-center gap-2 mt-2">
                    {{-- role_badge uses the getRoleBadgeAttribute accessor --}}
                    <span class="text-xs font-bold uppercase px-2.5 py-1 rounded-full
                                 {{ $employee->role_badge }}">
                        {{ $employee->role_label }}
                    </span>
                    @if($employee->department)
                        <span class="text-xs text-slate-400">
                            · {{ $employee->department }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- Avatar circle colored by role --}}
            <div class="hidden sm:flex h-16 w-16 rounded-full items-center justify-center
                        text-2xl font-bold ring-4 ring-white/20
                        {{ $employee->role_color }}">
                {{ strtoupper(substr($employee->name, 0, 1)) }}
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Total --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    Total
                </p>
                <div class="h-8 w-8 rounded-lg bg-slate-100 flex items-center justify-center">
                    <i class="bi bi-folder2 text-slate-600 text-sm"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-900">{{ $stats['total'] }}</p>
            <p class="text-xs text-slate-400 mt-1">All applications</p>
        </div>

        {{-- Pending --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    Pending
                </p>
                <div class="h-8 w-8 rounded-lg bg-yellow-100 flex items-center justify-center">
                    <i class="bi bi-hourglass-split text-yellow-600 text-sm"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
            <p class="text-xs text-slate-400 mt-1">Awaiting review</p>
        </div>

        {{-- Under Review --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    In Review
                </p>
                <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i class="bi bi-eye text-blue-600 text-sm"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-blue-600">{{ $stats['under_review'] }}</p>
            <p class="text-xs text-slate-400 mt-1">Being processed</p>
        </div>

        {{-- Issued --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    Issued
                </p>
                <div class="h-8 w-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <i class="bi bi-award text-emerald-600 text-sm"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-emerald-600">{{ $stats['issued'] }}</p>
            <p class="text-xs text-slate-400 mt-1">Permits issued</p>
        </div>

    </div>

    {{-- Second row of stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Approved --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    Approved
                </p>
                <div class="h-8 w-8 rounded-lg bg-green-100 flex items-center justify-center">
                    <i class="bi bi-check-circle text-green-600 text-sm"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-green-600">{{ $stats['approved'] }}</p>
            <p class="text-xs text-slate-400 mt-1">Awaiting payment</p>
        </div>

        {{-- Paid --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    Paid
                </p>
                <div class="h-8 w-8 rounded-lg bg-purple-100 flex items-center justify-center">
                    <i class="bi bi-credit-card text-purple-600 text-sm"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-purple-600">{{ $stats['paid'] }}</p>
            <p class="text-xs text-slate-400 mt-1">Payment submitted</p>
        </div>

        {{-- Rejected --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    Rejected
                </p>
                <div class="h-8 w-8 rounded-lg bg-red-100 flex items-center justify-center">
                    <i class="bi bi-x-circle text-red-600 text-sm"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-red-600">{{ $stats['rejected'] }}</p>
            <p class="text-xs text-slate-400 mt-1">Not approved</p>
        </div>

        {{-- Role-specific stat --}}
        @if($employee->isAdmin())
            {{-- Admin sees total active employees --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        Employees
                    </p>
                    <div class="h-8 w-8 rounded-lg bg-red-100 flex items-center justify-center">
                        <i class="bi bi-people text-red-600 text-sm"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold text-red-600">{{ $totalEmployees }}</p>
                <p class="text-xs text-slate-400 mt-1">Active employees</p>
            </div>

        @elseif($employee->isStaff())
            {{-- Staff sees how many they personally processed --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        My Work
                    </p>
                    <div class="h-8 w-8 rounded-lg bg-green-100 flex items-center justify-center">
                        <i class="bi bi-person-check text-green-600 text-sm"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold text-green-600">{{ $myProcessedCount }}</p>
                <p class="text-xs text-slate-400 mt-1">Processed by me</p>
            </div>
        @else
            {{-- Manager sees pending payment verifications --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        To Verify
                    </p>
                    <div class="h-8 w-8 rounded-lg bg-orange-100 flex items-center justify-center">
                        <i class="bi bi-clock-history text-orange-600 text-sm"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold text-orange-600">{{ $stats['paid'] }}</p>
                <p class="text-xs text-slate-400 mt-1">Payments to verify</p>
            </div>
        @endif

    </div>

    {{-- Recent Applications Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h2 class="text-base font-semibold text-slate-900">
                {{-- Title changes based on role --}}
                @if($employee->isStaff())
                    My Processed Applications
                @elseif($employee->isManager())
                    Applications Needing Attention
                @else
                    Recent Applications
                @endif
            </h2>

            {{-- Link to the full list — differs by role --}}
            @if($employee->isAdmin())
                <a href="{{ route('employee.admin.applications.index') }}"
                   class="text-xs text-blue-600 hover:underline font-semibold">
                    View All →
                </a>
            @elseif($employee->isManager())
                <a href="{{ route('employee.manager.applications.index') }}"
                   class="text-xs text-blue-600 hover:underline font-semibold">
                    View All →
                </a>
            @else
                <a href="{{ route('employee.staff.applications.index') }}"
                   class="text-xs text-blue-600 hover:underline font-semibold">
                    View All →
                </a>
            @endif
        </div>

        @if($recentApplications->isEmpty())
            <div class="text-center py-12">
                <i class="bi bi-inbox text-4xl text-slate-300 block mb-2"></i>
                <p class="text-sm text-slate-500">No applications to show</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="text-left px-5 py-3 text-xs font-semibold
                                       text-slate-500 uppercase tracking-wider">
                                App. No.
                            </th>
                            <th class="text-left px-5 py-3 text-xs font-semibold
                                       text-slate-500 uppercase tracking-wider">
                                Business
                            </th>
                            <th class="text-left px-5 py-3 text-xs font-semibold
                                       text-slate-500 uppercase tracking-wider">
                                Applicant
                            </th>
                            <th class="text-left px-5 py-3 text-xs font-semibold
                                       text-slate-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="text-left px-5 py-3 text-xs font-semibold
                                       text-slate-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th class="text-right px-5 py-3 text-xs font-semibold
                                       text-slate-500 uppercase tracking-wider">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($recentApplications as $app)
                        <tr class="hover:bg-slate-50 transition-colors">

                            <td class="px-5 py-3 font-mono text-xs text-slate-500">
                                {{ $app->application_number }}
                            </td>

                            <td class="px-5 py-3">
                                <p class="font-semibold text-slate-800 text-sm">
                                    {{ $app->business_name }}
                                </p>
                                <p class="text-xs text-slate-400">
                                    {{ $app->transact_type }}
                                </p>
                            </td>

                            <td class="px-5 py-3">
                                <p class="text-sm text-slate-700">
                                    {{ $app->user->name ?? 'Unknown' }}
                                </p>
                            </td>

                            <td class="px-5 py-3">
                                {{-- status_color and status_label are
                                     accessors from BusinessApplication model --}}
                                <span class="inline-flex items-center px-2.5 py-0.5
                                             rounded-full text-xs font-medium
                                             {{ $app->status_color }}">
                                    {{ $app->status_label }}
                                </span>
                            </td>

                            <td class="px-5 py-3 text-xs text-slate-400">
                                {{ $app->created_at->format('M d, Y') }}
                            </td>

                            <td class="px-5 py-3 text-right">
                                {{-- Different view links based on role --}}
                                @if($employee->isAdminOrManager())
                                    <a href="{{ route('employee.manager.applications.show', $app->id) }}"
                                       class="text-xs px-3 py-1.5 rounded-lg border
                                              border-slate-200 text-slate-600
                                              hover:bg-slate-100 transition-colors">
                                        Review
                                    </a>
                                @else
                                    <a href="{{ route('employee.staff.applications.show', $app->id) }}"
                                       class="text-xs px-3 py-1.5 rounded-lg border
                                              border-slate-200 text-slate-600
                                              hover:bg-slate-100 transition-colors">
                                        View
                                    </a>
                                @endif
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        {{-- Action 1: View Applications --}}
        @if($employee->isAdminOrManager())
            <a href="{{ route('employee.manager.applications.index') }}"
        @else
            <a href="{{ route('employee.staff.applications.index') }}"
        @endif
               class="flex items-center gap-4 bg-white rounded-2xl border border-slate-200
                      p-5 shadow-sm hover:shadow-md hover:border-blue-200
                      transition-all duration-200 group">
            <div class="h-12 w-12 rounded-xl bg-blue-100 flex items-center justify-center
                        group-hover:bg-blue-200 transition-colors flex-shrink-0">
                <i class="bi bi-file-earmark-text text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-slate-800">Applications</p>
                <p class="text-xs text-slate-400 mt-0.5">
                    {{ $stats['pending'] }} pending
                </p>
            </div>
        </a>

        {{-- Action 2: Role-specific second action --}}
        @if($employee->isAdmin())
            <a href="{{ route('employee.admin.employees.index') }}"
               class="flex items-center gap-4 bg-white rounded-2xl border border-slate-200
                      p-5 shadow-sm hover:shadow-md hover:border-red-200
                      transition-all duration-200 group">
                <div class="h-12 w-12 rounded-xl bg-red-100 flex items-center justify-center
                            group-hover:bg-red-200 transition-colors flex-shrink-0">
                    <i class="bi bi-people text-red-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-800">Manage Employees</p>
                    <p class="text-xs text-slate-400 mt-0.5">
                        {{ $totalEmployees }} active
                    </p>
                </div>
            </a>
        @elseif($employee->isManager())
            <a href="{{ route('employee.manager.applications.index') }}"
               class="flex items-center gap-4 bg-white rounded-2xl border border-slate-200
                      p-5 shadow-sm hover:shadow-md hover:border-yellow-200
                      transition-all duration-200 group">
                <div class="h-12 w-12 rounded-xl bg-yellow-100 flex items-center justify-center
                            group-hover:bg-yellow-200 transition-colors flex-shrink-0">
                    <i class="bi bi-clipboard-check text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-800">Review & Approve</p>
                    <p class="text-xs text-slate-400 mt-0.5">
                        {{ $stats['under_review'] }} under review
                    </p>
                </div>
            </a>
        @else
            <a href="{{ route('employee.staff.applications.index') }}"
               class="flex items-center gap-4 bg-white rounded-2xl border border-slate-200
                      p-5 shadow-sm hover:shadow-md hover:border-green-200
                      transition-all duration-200 group">
                <div class="h-12 w-12 rounded-xl bg-green-100 flex items-center justify-center
                            group-hover:bg-green-200 transition-colors flex-shrink-0">
                    <i class="bi bi-person-check text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-800">My Applications</p>
                    <p class="text-xs text-slate-400 mt-0.5">
                        {{ $myProcessedCount }} processed
                    </p>
                </div>
            </a>
        @endif

        {{-- Action 3: Notifications --}}
        <a href="{{ route('employee.notifications.index') }}"
           class="flex items-center gap-4 bg-white rounded-2xl border border-slate-200
                  p-5 shadow-sm hover:shadow-md hover:border-purple-200
                  transition-all duration-200 group">
            <div class="h-12 w-12 rounded-xl bg-purple-100 flex items-center justify-center
                        group-hover:bg-purple-200 transition-colors flex-shrink-0 relative">
                <i class="bi bi-bell text-purple-600 text-xl"></i>
                @if($employee->unreadNotificationsCount() > 0)
                    <span class="absolute -top-1 -right-1 h-4 w-4 bg-red-500 text-white
                                 text-[10px] font-bold rounded-full flex items-center
                                 justify-center">
                        {{ $employee->unreadNotificationsCount() }}
                    </span>
                @endif
            </div>
            <div>
                <p class="text-sm font-semibold text-slate-800">Notifications</p>
                <p class="text-xs text-slate-400 mt-0.5">
                    {{ $employee->unreadNotificationsCount() }} unread
                </p>
            </div>
        </a>

    </div>

</div>
@endsection