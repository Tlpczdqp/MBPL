<nav class="bg-white border-b border-slate-200 px-4 py-2.5 sticky top-0 z-20 shadow-sm">
    <div class="flex items-center justify-between w-full">

        <div class="flex items-center gap-3">
            <button id="sidebar-toggle"
                    class="p-2 rounded-md text-slate-600 hover:bg-slate-100 lg:hidden focus:outline-none"
                    aria-label="Toggle Sidebar">
                <i class="bi bi-list text-xl"></i>
            </button>
            <span class="text-base font-semibold text-slate-800">Employee Portal</span>
        </div>

        @php
            $employee    = \Illuminate\Support\Facades\Auth::guard('employee')->user();

            // Use appNotifications() not notifications()
            $unreadCount = $employee->unreadNotificationsCount();

            $avatarColor = match($employee->role) {
                'admin'   => 'bg-red-600',
                'manager' => 'bg-yellow-500',
                default   => 'bg-green-600',
            };

            $badgeColor = match($employee->role) {
                'admin'   => 'bg-red-100 text-red-700',
                'manager' => 'bg-yellow-100 text-yellow-700',
                default   => 'bg-green-100 text-green-700',
            };
        @endphp

        <div class="flex items-center gap-2 mr-1">

            {{-- Notification Bell --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                        class="relative p-2 rounded-full text-slate-600 hover:bg-slate-100
                               focus:outline-none transition-colors">
                    <i class="bi bi-bell text-xl"></i>
                    @if($unreadCount > 0)
                        <span class="absolute top-1 right-1 h-4 w-4 bg-red-500 text-white
                                     text-[10px] font-bold rounded-full flex items-center
                                     justify-center leading-none">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                    @endif
                </button>

                <div x-show="open"
                     @click.outside="open = false"
                     x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-80 bg-white border border-slate-200
                            rounded-xl shadow-xl z-50 overflow-hidden">

                    <div class="px-4 py-3 border-b border-slate-100 flex items-center
                                justify-between">
                        <h3 class="text-sm font-semibold text-slate-900">Notifications</h3>
                        @if($unreadCount > 0)
                            <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5
                                         rounded-full font-medium">
                                {{ $unreadCount }} new
                            </span>
                        @endif
                    </div>

                    <div class="max-h-72 overflow-y-auto divide-y divide-slate-50">

                        {{-- Use appNotifications() not notifications() --}}
                        @forelse($employee->appNotifications()->latest()->take(5)->get()
                                 as $notif)
                            <div class="block px-4 py-3 hover:bg-slate-50 transition-colors
                                        {{ !$notif->is_read ? 'bg-blue-50' : '' }}">
                                <div class="flex items-start gap-3">
                                    <span class="mt-1.5 h-2 w-2 rounded-full flex-shrink-0
                                                 {{ !$notif->is_read ? 'bg-blue-500' : 'bg-transparent' }}">
                                    </span>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-slate-800 truncate">
                                            {{ $notif->title }}
                                        </p>
                                        <p class="text-xs text-slate-500 mt-0.5 line-clamp-2">
                                            {{ $notif->message }}
                                        </p>
                                        <p class="text-[10px] text-slate-400 mt-1">
                                            {{ $notif->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-4 py-8 text-center">
                                <i class="bi bi-bell-slash text-2xl text-slate-300 block mb-2"></i>
                                <p class="text-sm text-slate-400">No notifications yet</p>
                            </div>
                        @endforelse

                    </div>
                </div>
            </div>
            
            {{-- Profile Dropdown --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                        class="flex items-center gap-2 text-slate-700 hover:text-slate-900
                               text-sm font-medium px-3 py-2 rounded-lg transition-all
                               hover:bg-slate-100 focus:outline-none">

                    <div class="h-7 w-7 rounded-full {{ $avatarColor }} flex items-center
                                justify-center text-white text-xs font-bold">
                        {{ strtoupper(substr($employee->name, 0, 1)) }}
                    </div>

                    <span class="hidden sm:inline">{{ $employee->name }}</span>

                    <span class="hidden sm:inline text-[10px] font-bold uppercase px-1.5
                                 py-0.5 rounded {{ $badgeColor }}">
                        {{ $employee->role }}
                    </span>

                    <i class="bi bi-chevron-down text-xs hidden sm:inline transition-transform
                               duration-300"
                       :class="{ 'rotate-180': open }"></i>
                </button>

                <div x-show="open"
                     @click.outside="open = false"
                     x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-52 bg-white border border-slate-200
                            rounded-xl shadow-lg z-50 py-1">

                    <div class="px-4 py-2 border-b border-slate-100">
                        <p class="text-xs font-semibold text-slate-900 truncate">
                            {{ $employee->name }}
                        </p>
                        <p class="text-xs text-slate-400 truncate">
                            {{ ucfirst($employee->role) }}
                        </p>
                    </div>

                    <div class="border-t border-slate-100 mt-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center gap-2 px-4 py-2 text-sm
                                           text-red-600 hover:bg-red-50 transition-colors">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
</nav>