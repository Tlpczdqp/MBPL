@extends('layouts.app')
@section('title', 'Dashboard')

@push('meta_refresh')
    <meta http-equiv="refresh" content="60">
@endpush


@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- Welcome Banner --}}
    <div class="bg-gradient-to-r from-slate-800 to-slate-700 rounded-2xl p-6 text-white shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-400 text-sm mb-1">Welcome back,</p>
                <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
                <p class="text-slate-400 text-sm mt-1">{{ $user->email }}</p>
            </div>
            {{-- Avatar --}}
            <div class="hidden sm:flex h-16 w-16 rounded-full bg-slate-600
                        items-center justify-center text-2xl font-bold ring-4 ring-slate-600">
                @if($user->avatar)
                    <img src="{{ $user->avatar }}"
                         alt="Avatar"
                         class="h-16 w-16 rounded-full object-cover" />
                @else
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                @endif
            </div>
        </div>
    </div>

    {{-- Unread Notifications --}}
    @if($unreadNotifications->isNotEmpty())
        <div class="space-y-2">
            @foreach($unreadNotifications as $notif)
                <div class="flex items-start gap-3 bg-blue-50 border border-blue-200
                            rounded-xl px-4 py-3"
                     x-data="{ show: true }"
                     x-show="show">
                    <span class="mt-1 h-2 w-2 rounded-full bg-blue-500 flex-shrink-0"></span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-blue-900">{{ $notif->title }}</p>
                        <p class="text-xs text-blue-700 mt-0.5">{{ $notif->message }}</p>
                    </div>
                    {{-- Dismiss button marks it as read --}}
                    <button @click="
                                show = false;
                                fetch('{{ route('user.notifications.read', ['userId' => auth()->id(), 'notification' => $notif->id]) }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    }
                                });
                            "
                            class="text-blue-400 hover:text-blue-600 flex-shrink-0">
                        <i class="bi bi-x text-lg"></i>
                    </button>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">

        {{-- Total --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm
                    col-span-2 lg:col-span-1">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    Total
                </p>
                <div class="h-8 w-8 rounded-lg bg-slate-100 flex items-center justify-center">
                    <i class="bi bi-folder2 text-slate-600"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-900">{{ $stats['total'] }}</p>
            <p class="text-xs text-slate-400 mt-1">Applications</p>
        </div>

        {{-- Pending --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    Pending
                </p>
                <div class="h-8 w-8 rounded-lg bg-yellow-100 flex items-center justify-center">
                    <i class="bi bi-hourglass-split text-yellow-600"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
            <p class="text-xs text-slate-400 mt-1">Awaiting review</p>
        </div>

        {{-- Approved --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    Approved
                </p>
                <div class="h-8 w-8 rounded-lg bg-green-100 flex items-center justify-center">
                    <i class="bi bi-check-circle text-green-600"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-green-600">{{ $stats['approved'] }}</p>
            <p class="text-xs text-slate-400 mt-1">Approved / Paid</p>
        </div>

        {{-- Issued --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    Issued
                </p>
                <div class="h-8 w-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <i class="bi bi-award text-emerald-600"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-emerald-600">{{ $stats['issued'] }}</p>
            <p class="text-xs text-slate-400 mt-1">Permits issued</p>
        </div>

        {{-- Rejected --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    Rejected
                </p>
                <div class="h-8 w-8 rounded-lg bg-red-100 flex items-center justify-center">
                    <i class="bi bi-x-circle text-red-600"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-red-600">{{ $stats['rejected'] }}</p>
            <p class="text-xs text-slate-400 mt-1">Not approved</p>
        </div>

    </div>

    {{-- Recent Applications Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h2 class="text-base font-semibold text-slate-900">Recent Applications</h2>
            <a href="{{ route('user.business.index', ['userId' => auth()->id()]) }}"
               class="text-xs text-blue-600 hover:text-blue-800 font-semibold hover:underline">
                View All →
            </a>
        </div>

        @if($recentApplications->isEmpty())
            <div class="text-center py-12">
                <i class="bi bi-folder-x text-4xl text-slate-300 block mb-2"></i>
                <p class="text-sm text-slate-500 font-medium">No applications yet</p>
                <p class="text-xs text-slate-400 mt-1 mb-4">
                    Start by applying for your first business permit.
                </p>
                <a href="{{ route('user.business.create', ['userId' => auth()->id()]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900
                          hover:bg-slate-700 text-white text-xs font-semibold
                          rounded-lg transition-colors">
                    <i class="bi bi-plus-lg"></i> Apply Now
                </a>
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
                                Business Name
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
                                <p class="font-semibold text-slate-800">
                                    {{ $app->business_name }}
                                </p>
                                <p class="text-xs text-slate-400">
                                    {{ $app->transact_type }}
                                </p>
                            </td>

                            <td class="px-5 py-3">
                                {{-- Use the accessor from the model --}}
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
                                <a href="{{ route('user.business.show', ['userId' => auth()->id(), 'application' => $app->id]) }}"
                                   class="text-xs px-3 py-1.5 rounded-lg border border-slate-200
                                          text-slate-600 hover:bg-slate-100 transition-colors">
                                    View
                                </a>
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

        <a href="{{ route('user.business.create', ['userId' => auth()->id()]) }}"
           class="flex items-center gap-4 bg-white rounded-2xl border border-slate-200
                  p-5 shadow-sm hover:shadow-md hover:border-blue-200
                  transition-all duration-200 group">
            <div class="h-12 w-12 rounded-xl bg-blue-100 flex items-center justify-center
                        group-hover:bg-blue-200 transition-colors flex-shrink-0">
                <i class="bi bi-file-earmark-plus text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-slate-800">Apply for Permit</p>
                <p class="text-xs text-slate-400 mt-0.5">New business application</p>
            </div>
        </a>

        <a href="{{ route('user.business.index', ['userId' => auth()->id()]) }}"
           class="flex items-center gap-4 bg-white rounded-2xl border border-slate-200
                  p-5 shadow-sm hover:shadow-md hover:border-green-200
                  transition-all duration-200 group">
            <div class="h-12 w-12 rounded-xl bg-green-100 flex items-center justify-center
                        group-hover:bg-green-200 transition-colors flex-shrink-0">
                <i class="bi bi-folder2-open text-green-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-slate-800">My Applications</p>
                <p class="text-xs text-slate-400 mt-0.5">Track all your applications</p>
            </div>
        </a>

        <a href="{{ route('user.profile', ['userId' => auth()->id()]) }}"
           class="flex items-center gap-4 bg-white rounded-2xl border border-slate-200
                  p-5 shadow-sm hover:shadow-md hover:border-purple-200
                  transition-all duration-200 group">
            <div class="h-12 w-12 rounded-xl bg-purple-100 flex items-center justify-center
                        group-hover:bg-purple-200 transition-colors flex-shrink-0">
                <i class="bi bi-person-circle text-purple-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-slate-800">My Profile</p>
                <p class="text-xs text-slate-400 mt-0.5">View and edit your profile</p>
            </div>
        </a>

    </div>

</div>
@endsection