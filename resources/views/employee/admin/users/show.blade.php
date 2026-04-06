@extends('layouts.employee')

@section('title', 'User Profile')

@section('content')

{{-- Back + Header --}}
<div class="flex items-center gap-4 mb-6">
    <a href="{{ route('employee.admin.users.index') }}"
       class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-slate-200
              hover:bg-slate-50 text-slate-500 transition-colors shadow-sm">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-800">User Profile</h1>
        <p class="text-sm text-slate-500 mt-0.5">Viewing details for {{ $user->name }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Profile Card --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col items-center text-center h-fit">

        {{-- Avatar --}}
        @if($user->avatar)
            <img src="{{ $user->avatar }}"
                 alt="{{ $user->name }}"
                 class="w-20 h-20 rounded-full object-cover shadow-md mb-4" />
        @else
            <div class="w-20 h-20 rounded-full bg-[#1e2a4a] flex items-center justify-center
                        text-white text-3xl font-bold mb-4 shadow-md">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
        @endif

        <h2 class="text-lg font-bold text-slate-800">{{ $user->name }}</h2>
        <p class="text-sm text-slate-500 mt-1">{{ $user->email }}</p>

        {{-- Account Status Badge --}}
        @if($user->deleted_at)
            <span class="mt-2 inline-flex items-center gap-1 bg-red-100 text-red-600
                         text-xs font-semibold px-3 py-1 rounded-full">
                <i class="bi bi-slash-circle"></i> Deactivated
            </span>
        @else
            <span class="mt-2 inline-flex items-center gap-1 bg-green-100 text-green-700
                         text-xs font-semibold px-3 py-1 rounded-full">
                <i class="bi bi-check-circle-fill"></i> Active
            </span>
        @endif

        {{-- Email Verified Badge --}}
        @if($user->email_verified)
            <span class="mt-2 inline-flex items-center gap-1 bg-blue-100 text-blue-700
                         text-xs font-semibold px-3 py-1 rounded-full">
                <i class="bi bi-patch-check-fill"></i> Email Verified
            </span>
        @else
            <span class="mt-2 inline-flex items-center gap-1 bg-amber-100 text-amber-700
                         text-xs font-semibold px-3 py-1 rounded-full">
                <i class="bi bi-exclamation-circle"></i> Email Unverified
            </span>
        @endif

        {{-- Meta Info --}}
        <div class="mt-5 w-full space-y-3 border-t border-slate-100 pt-4 text-left">

            <div>
                <p class="text-xs text-slate-400 uppercase tracking-wide mb-0.5">Phone</p>
                <p class="text-sm font-medium text-slate-700">{{ $user->phone ?? '—' }}</p>
            </div>

            <div>
                <p class="text-xs text-slate-400 uppercase tracking-wide mb-0.5">Registered</p>
                <p class="text-sm font-medium text-slate-700">
                    {{ $user->created_at->format('F d, Y') }}
                </p>
            </div>

            @if($user->google_id)
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wide mb-0.5">Login Method</p>
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold
                                 px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">
                        <i class="bi bi-google"></i> Google Account
                    </span>
                </div>
            @elseif($user->facebook_id)
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wide mb-0.5">Login Method</p>
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold
                                 px-2.5 py-1 rounded-full bg-blue-100 text-blue-600">
                        <i class="bi bi-facebook"></i> Facebook Account
                    </span>
                </div>
            @endif

            @if($user->deleted_at)
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wide mb-0.5">Deactivated On</p>
                    <p class="text-sm font-medium text-red-600">
                        {{ $user->deleted_at->format('F d, Y') }}
                    </p>
                </div>
            @endif

        </div>

        {{-- Action Buttons --}}
        <div class="mt-5 w-full space-y-2 border-t border-slate-100 pt-4">
            @if($user->deleted_at)
                <form action="{{ route('employee.admin.users.restore', $user->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 bg-green-500
                                   hover:bg-green-600 text-white text-sm font-semibold
                                   py-2.5 rounded-lg transition-colors">
                        <i class="bi bi-arrow-counterclockwise"></i> Restore Account
                    </button>
                </form>
            @else
                <form action="{{ route('employee.admin.users.destroy', $user->id) }}" method="POST"
                      x-data
                      @submit.prevent="if(confirm('Deactivate {{ addslashes($user->name) }}?')) $el.submit()">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 bg-red-500
                                   hover:bg-red-600 text-white text-sm font-semibold
                                   py-2.5 rounded-lg transition-colors">
                        <i class="bi bi-person-slash"></i> Deactivate Account
                    </button>
                </form>
            @endif
        </div>

    </div>

    {{-- Applications --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center">
                    <i class="bi bi-file-earmark-text text-blue-600"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">Business Applications</h3>
                    <p class="text-xs text-slate-400">
                        {{ $user->businessApplications->count() }} application(s) submitted
                    </p>
                </div>
            </div>
        </div>

        @if($user->businessApplications->isEmpty())
            <div class="flex flex-col items-center justify-center py-12 text-center">
                <div class="w-14 h-14 bg-slate-100 rounded-full flex items-center justify-center mb-3">
                    <i class="bi bi-file-earmark text-slate-400 text-xl"></i>
                </div>
                <p class="text-slate-600 font-medium text-sm">No applications yet</p>
                <p class="text-slate-400 text-xs mt-1">
                    This user has not submitted any business permit applications.
                </p>
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($user->businessApplications as $app)
                    <div class="flex items-center justify-between px-6 py-4 hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0">
                                <i class="bi bi-building text-slate-500"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-800 text-sm">
                                    {{ $app->business_name }}
                                </p>
                                <p class="text-xs text-slate-400">{{ $app->application_number }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            @php
                                $statusConfig = [
                                    'pending'       => 'bg-amber-100 text-amber-700',
                                    'under_review'  => 'bg-blue-100 text-blue-700',
                                    'approved'      => 'bg-green-100 text-green-700',
                                    'paid'          => 'bg-teal-100 text-teal-700',
                                    'rejected'      => 'bg-red-100 text-red-700',
                                    'permit_issued' => 'bg-purple-100 text-purple-700',
                                ];
                                $cls = $statusConfig[$app->status] ?? 'bg-slate-100 text-slate-600';
                            @endphp
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $cls }}">
                                {{ ucfirst(str_replace('_', ' ', $app->status)) }}
                            </span>
                            <span class="text-xs text-slate-400 hidden sm:block">
                                {{ $app->created_at->format('M d, Y') }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

</div>

@endsection