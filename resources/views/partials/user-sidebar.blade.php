{{-- ============================================================
     USER SIDEBAR
     - Shows logo, navigation links for regular users
     - On mobile: hidden off-screen (-translate-x-full), slides in when toggled
     - On desktop (lg+): always visible (lg:translate-x-0)
     ============================================================ --}}

{{-- Dark overlay (mobile only) — clicking it closes the sidebar --}}
<div id="sidebar-overlay"
     class="fixed inset-0 z-30 bg-black/50 hidden lg:hidden transition-opacity duration-300">
</div>

{{-- SIDEBAR CONTAINER --}}
<div id="sidebar"
     class="bg-slate-800 text-white w-[250px] h-screen fixed top-0 left-0
            overflow-y-auto border-r border-slate-700 z-40
            transition-transform duration-300 ease-in-out
            -translate-x-full lg:translate-x-0">

    {{-- ── SIDEBAR HEADER ────────────────────────────────────── --}}
    <div class="flex items-center justify-between p-4 border-b border-slate-700">
        {{-- Logo: upload your image to public/images/logo.png --}}
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/logo.png') }}"
                 alt="Logo"
                 class="h-9 w-9 object-contain rounded-full bg-slate-600 p-1"
                 onerror="this.src='https://placehold.co/36x36/475569/white?text=BP'" />
            <span class="text-sm font-bold text-white leading-tight">
                Municipal<br>Business Permit<br>& Licensing
            </span>
        </div>

        {{-- Close button (mobile only) --}}
        <button id="sidebar-close"
                class="text-slate-300 hover:text-white lg:hidden focus:outline-none"
                aria-label="Close Sidebar">
            <i class="bi bi-x-lg text-lg"></i>
        </button>
    </div>

    {{-- ── USER INFO CARD ─────────────────────────────────────── --}}
    {{-- Shows the logged-in user's avatar and name --}}
    {{-- <div class="p-4 border-b border-slate-700">
        <div class="flex items-center gap-3">
            @if(auth()->user()->avatar)
                <img src="{{ auth()->user()->avatar }}"
                     alt="Avatar"
                     class="h-10 w-10 rounded-full object-cover ring-2 ring-slate-600" />
            @else --}}
                {{-- Default avatar using user's first letter --}}
                {{-- <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center
                            text-white font-bold text-sm ring-2 ring-slate-600">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            @endif
            <div class="min-w-0">
                <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email }}</p>
            </div>
        </div>
    </div>asd --}}


    {{-- ── NAVIGATION LINKS ────────────────────────────────────── --}}
    <nav class="p-3">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 px-2">
            Main Menu
        </p>

        <ul class="flex flex-col space-y-1">

            {{-- My Business Applications --}}
            {{-- request()->routeIs() checks if the current URL matches the route name --}}
            {{-- If it matches, we highlight with a different background (active state) --}}
            <li>
                <a href="{{ route('user.dashboard', ['userId' => auth()->id()]) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200
                          {{ request()->routeIs('user.dashboard')
                             ? 'bg-blue-600 text-white font-semibold shadow-sm'
                             : 'text-slate-300 hover:text-white hover:bg-slate-700' }}">
                    <i class="bi bi-folder2-open text-base"></i>
                    Dashboard
                </a>
            </li>


            <li>
                <a href="{{ route('user.business.index', ['userId' => auth()->id()]) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200
                          {{ request()->routeIs('user.business.index')
                             ? 'bg-blue-600 text-white font-semibold shadow-sm'
                             : 'text-slate-300 hover:text-white hover:bg-slate-700' }}">
                    <i class="bi bi-folder2-open text-base"></i>
                    My Business Applications
                </a>
            </li>

            {{-- Apply for a Business Permit --}}
            <li>
                <a href="{{ route('user.business.create', ['userId' => auth()->id()]) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200
                          {{ request()->routeIs('user.business.create')
                             ? 'bg-blue-600 text-white font-semibold shadow-sm'
                             : 'text-slate-300 hover:text-white hover:bg-slate-700' }}">
                    <i class="bi bi-file-earmark-plus text-base"></i>
                    Apply for a Permit
                </a>
            </li>

            {{-- Renew Business Permit --}}
            {{-- This links to the user's applications list where they can choose which to renew --}}
            <li>
                <a href="{{ route('user.business.index', ['userId' => auth()->id()]) }}?filter=renew"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200
                          text-slate-300 hover:text-white hover:bg-slate-700">
                    <i class="bi bi-arrow-clockwise text-base"></i>
                    Renew Business Permit
                </a>
            </li>

        </ul>
    </nav>

    {{-- ── SIDEBAR FOOTER ──────────────────────────────────────── --}}
    <div class="absolute bottom-0 left-0 right-0 p-3 border-t border-slate-700">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm
                           text-slate-300 hover:text-red-400 hover:bg-slate-700 transition-all duration-200">
                <i class="bi bi-box-arrow-left text-base"></i>
                Logout
            </button>
        </form>
    </div>

</div>

{{-- ── SIDEBAR TOGGLE SCRIPT ────────────────────────────────────── --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar  = document.getElementById('sidebar');
        const overlay  = document.getElementById('sidebar-overlay');
        const toggleBtn = document.getElementById('sidebar-toggle');
        const closeBtn  = document.getElementById('sidebar-close');
        let isOpen = false;

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
            overlay.classList.remove('hidden');
            isOpen = true;
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            sidebar.classList.remove('translate-x-0');
            overlay.classList.add('hidden');
            isOpen = false;
        }

        if (toggleBtn) toggleBtn.addEventListener('click', () => isOpen ? closeSidebar() : openSidebar());
        if (closeBtn)  closeBtn.addEventListener('click', closeSidebar);
        if (overlay)   overlay.addEventListener('click', closeSidebar);

        document.addEventListener('keydown', e => { if (e.key === 'Escape' && isOpen) closeSidebar(); });
        window.addEventListener('resize',    () => { if (window.innerWidth >= 1024) closeSidebar(); });
    });
</script>