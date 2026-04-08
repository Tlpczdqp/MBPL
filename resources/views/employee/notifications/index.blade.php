@extends('layouts.employee')

@section('title', 'Notifications')

@section('content')
<div>
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Notifications</h1>
            <p class="text-sm text-slate-500 mt-1">Stay updated with your latest activities</p>
        </div>

        @if($notifications->where('is_read', false)->count() > 0)
            <form action="{{ route('employee.notifications.readAll') }}" method="POST" class="mt-3 sm:mt-0">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                    <i class="bi bi-check2-all"></i> Mark All as Read
                </button>
            </form>
        @endif
    </div>

    {{-- Notifications List --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">

        @forelse($notifications as $notification)
            <div class="flex items-start gap-4 px-6 py-4 border-b border-slate-100 transition
                        {{ $notification->is_read ? 'bg-white' : 'bg-blue-50/50' }}"
                 x-data="{ read: {{ $notification->is_read ? 'true' : 'false' }} }">

                {{-- Icon --}}
                <div class="flex-shrink-0 mt-0.5">
                    @php
                        $iconMap = [
                            'Application Submitted'    => ['bi-send',          'bg-blue-100',   'text-blue-600'],
                            'Application Under Review' => ['bi-search',        'bg-yellow-100', 'text-yellow-600'],
                            'Application Approved'     => ['bi-check-circle',  'bg-green-100',  'text-green-600'],
                            'Application Rejected'     => ['bi-x-circle',      'bg-red-100',    'text-red-600'],
                            'Payment Verified'         => ['bi-credit-card',   'bg-emerald-100','text-emerald-600'],
                            'Business Permit Issued'   => ['bi-award',         'bg-purple-100', 'text-purple-600'],
                        ];
                        $matched = collect($iconMap)->first(function ($val, $key) use ($notification) {
                            return str_contains($notification->title, $key);
                        });
                        $icon = $matched ?? ['bi-bell', 'bg-slate-100', 'text-slate-600'];
                    @endphp
                    <div class="w-10 h-10 rounded-lg {{ $icon[1] }} flex items-center justify-center">
                        <i class="bi {{ $icon[0] }} {{ $icon[2] }} text-lg"></i>
                    </div>
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-semibold text-slate-800 {{ $notification->is_read ? '' : 'text-blue-900' }}">
                                {{ $notification->title }}
                            </p>
                            <p class="text-sm text-slate-600 mt-0.5">{{ $notification->message }}</p>
                            <p class="text-xs text-slate-400 mt-1.5">
                                <i class="bi bi-clock mr-0.5"></i>
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-2 flex-shrink-0">
                            {{-- Link --}}
                            @if($notification->link)
                                <a href="{{ $notification->link }}"
                                   class="inline-flex items-center gap-1 rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-50">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            @endif

                            {{-- Mark as Read --}}
                            @if(!$notification->is_read)
                                <button type="button"
                                        x-show="!read"
                                        @click="
                                            fetch('{{ route('employee.notifications.read', $notification->id) }}', {
                                                method: 'POST',
                                                headers: {
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                    'Content-Type': 'application/json',
                                                },
                                            }).then(() => { read = true; $el.closest('[x-data]').classList.remove('bg-blue-50/50'); });
                                        "
                                        class="inline-flex items-center gap-1 rounded-lg border border-blue-300 bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-700 transition hover:bg-blue-100">
                                    <i class="bi bi-check2"></i> Read
                                </button>
                            @endif

                            {{-- Unread Dot --}}
                            @if(!$notification->is_read)
                                <div x-show="!read" class="w-2.5 h-2.5 rounded-full bg-blue-500 flex-shrink-0"></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            {{-- Empty State --}}
            <div class="text-center py-16">
                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                    <i class="bi bi-bell-slash text-3xl text-slate-300"></i>
                </div>
                <p class="text-sm font-medium text-slate-500">No notifications yet</p>
                <p class="text-xs text-slate-400 mt-1">You'll be notified when there are updates</p>
            </div>
        @endforelse

    </div>

    {{-- Pagination --}}
    @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection