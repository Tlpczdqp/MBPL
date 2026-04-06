@extends('layouts.employee')

@section('title', 'Manage Users')

@section('content')

{{-- Page Header --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Manage Users</h1>
        <p class="text-sm text-slate-500 mt-1">View and manage registered business permit applicants.</p>
    </div>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    {{-- Total --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-slate-100 flex items-center justify-center flex-shrink-0">
            <i class="bi bi-people-fill text-slate-600 text-lg"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800">{{ $counts['total'] }}</p>
            <p class="text-xs text-slate-500">Total Users</p>
        </div>
    </div>

    {{-- Active --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
            <i class="bi bi-person-check-fill text-green-600 text-lg"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800">{{ $counts['active'] }}</p>
            <p class="text-xs text-slate-500">Active</p>
        </div>
    </div>

    {{-- Deactivated --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
            <i class="bi bi-person-slash text-red-500 text-lg"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800">{{ $counts['inactive'] }}</p>
            <p class="text-xs text-slate-500">Deactivated</p>
        </div>
    </div>

    {{-- Unverified --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
            <i class="bi bi-person-exclamation text-amber-500 text-lg"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800">{{ $counts['unverified'] }}</p>
            <p class="text-xs text-slate-500">Unverified</p>
        </div>
    </div>

</div>

{{-- Filters --}}
<div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 mb-4">
    <form method="GET" action="{{ route('employee.admin.users.index') }}"
          class="flex flex-col sm:flex-row gap-3">

        {{-- Search --}}
        <div class="relative flex-1">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                <i class="bi bi-search"></i>
            </span>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by name, email, or phone..."
                class="w-full pl-9 pr-4 py-2.5 border border-slate-300 rounded-lg text-sm
                       focus:outline-none focus:ring-2 focus:ring-[#1e2a4a]"
            />
        </div>

        {{-- Status Filter --}}
        <select name="status"
                class="px-4 py-2.5 border border-slate-300 rounded-lg text-sm text-slate-700
                       focus:outline-none focus:ring-2 focus:ring-[#1e2a4a] bg-white">
            <option value="">All Status</option>
            <option value="active"     {{ request('status') === 'active'     ? 'selected' : '' }}>Active</option>
            <option value="inactive"   {{ request('status') === 'inactive'   ? 'selected' : '' }}>Deactivated</option>
            <option value="unverified" {{ request('status') === 'unverified' ? 'selected' : '' }}>Unverified</option>
        </select>

        {{-- Filter Button --}}
        <button type="submit"
                class="flex items-center gap-2 bg-[#1e2a4a] hover:bg-[#16213a] text-white
                       text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
            <i class="bi bi-funnel"></i> Filter
        </button>

        {{-- Clear Button --}}
        @if(request()->hasAny(['search', 'status']))
            <a href="{{ route('employee.admin.users.index') }}"
               class="flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-600
                      text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                <i class="bi bi-x"></i> Clear
            </a>
        @endif

    </form>
</div>

{{-- Users Table --}}
<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">

    @if($users->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                <i class="bi bi-people text-slate-400 text-2xl"></i>
            </div>
            <p class="text-slate-600 font-medium">No users found</p>
            <p class="text-slate-400 text-sm mt-1">Try adjusting your search or filters.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">

                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide px-5 py-3">
                            User
                        </th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide px-5 py-3 hidden md:table-cell">
                            Phone
                        </th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide px-5 py-3 hidden sm:table-cell">
                            Verified
                        </th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide px-5 py-3 hidden lg:table-cell">
                            Registered
                        </th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide px-5 py-3">
                            Status
                        </th>
                        <th class="text-right text-xs font-semibold text-slate-500 uppercase tracking-wide px-5 py-3">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @foreach($users as $user)
                        <tr class="hover:bg-slate-50 transition-colors"
                            x-data="{ deactivateModal: false }">

                            {{-- User --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    {{-- Avatar --}}
                                    @if($user->avatar)
                                        <img src="{{ $user->avatar }}"
                                             alt="{{ $user->name }}"
                                             class="w-9 h-9 rounded-full object-cover flex-shrink-0" />
                                    @else
                                        <div class="w-9 h-9 rounded-full bg-[#1e2a4a] flex items-center justify-center
                                                    text-white text-sm font-bold flex-shrink-0">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $user->name }}</p>
                                        <p class="text-xs text-slate-400">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Phone --}}
                            <td class="px-5 py-4 hidden md:table-cell text-slate-500 text-sm">
                                {{ $user->phone ?? '—' }}
                            </td>

                            {{-- Email Verified --}}
                            <td class="px-5 py-4 hidden sm:table-cell">
                                @if($user->email_verified)
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold
                                                 px-2.5 py-1 rounded-full bg-green-100 text-green-700">
                                        <i class="bi bi-patch-check-fill"></i> Verified
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold
                                                 px-2.5 py-1 rounded-full bg-amber-100 text-amber-700">
                                        <i class="bi bi-exclamation-circle"></i> Unverified
                                    </span>
                                @endif
                            </td>

                            {{-- Registered --}}
                            <td class="px-5 py-4 hidden lg:table-cell text-slate-500 text-xs">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>

                            {{-- Account Status --}}
                            <td class="px-5 py-4">
                                @if($user->deleted_at)
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold
                                                 px-2.5 py-1 rounded-full bg-red-100 text-red-600">
                                        <i class="bi bi-slash-circle"></i> Deactivated
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold
                                                 px-2.5 py-1 rounded-full bg-green-100 text-green-700">
                                        <i class="bi bi-check-circle-fill"></i> Active
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">

                                    {{-- View --}}
                                    <a href="{{ route('employee.admin.users.show', $user->id) }}"
                                       class="w-8 h-8 flex items-center justify-center rounded-lg
                                              bg-slate-100 hover:bg-[#1e2a4a] text-slate-500 hover:text-white
                                              transition-colors duration-200"
                                       title="View">
                                        <i class="bi bi-eye text-xs"></i>
                                    </a>

                                    {{-- Restore or Deactivate --}}
                                    @if($user->deleted_at)
                                        <form action="{{ route('employee.admin.users.restore', $user->id) }}"
                                              method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="w-8 h-8 flex items-center justify-center rounded-lg
                                                           bg-slate-100 hover:bg-green-500 text-slate-500 hover:text-white
                                                           transition-colors duration-200"
                                                    title="Restore">
                                                <i class="bi bi-arrow-counterclockwise text-xs"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button type="button"
                                                @click="deactivateModal = true"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg
                                                       bg-slate-100 hover:bg-red-500 text-slate-500 hover:text-white
                                                       transition-colors duration-200"
                                                title="Deactivate">
                                            <i class="bi bi-person-slash text-xs"></i>
                                        </button>
                                    @endif

                                </div>

                                {{-- Deactivate Modal --}}
                                <div x-show="deactivateModal" x-cloak
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0"
                                     x-transition:enter-end="opacity-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100"
                                     x-transition:leave-end="opacity-0"
                                     class="fixed inset-0 z-50 flex items-center justify-center
                                            bg-black/50 backdrop-blur-sm px-4">
                                    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6"
                                         @click.outside="deactivateModal = false"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100">

                                        <div class="flex justify-center mb-4">
                                            <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center">
                                                <i class="bi bi-person-slash text-red-600 text-2xl"></i>
                                            </div>
                                        </div>

                                        <h3 class="text-center text-base font-bold text-slate-800 mb-1">
                                            Deactivate User
                                        </h3>
                                        <p class="text-center text-sm text-slate-500 mb-5">
                                            Are you sure you want to deactivate
                                            <strong>{{ $user->name }}</strong>?
                                            They will lose access immediately.
                                        </p>

                                        <div class="flex gap-3">
                                            <button type="button"
                                                    @click="deactivateModal = false"
                                                    class="flex-1 py-2.5 rounded-lg text-sm font-semibold
                                                           bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors">
                                                Cancel
                                            </button>
                                            <form action="{{ route('employee.admin.users.destroy', $user->id) }}"
                                                  method="POST" class="flex-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="w-full py-2.5 rounded-lg text-sm font-semibold
                                                               bg-red-600 hover:bg-red-700 text-white transition-colors shadow">
                                                    Deactivate
                                                </button>
                                            </form>
                                        </div>

                                    </div>
                                </div>

                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        @endif

    @endif
</div>

@endsection