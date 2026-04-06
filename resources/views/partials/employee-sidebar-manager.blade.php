<div id="sidebar-overlay"
     class="fixed inset-0 z-30 bg-black/50 hidden lg:hidden"></div>

<div id="sidebar"
     class="bg-slate-900 text-white w-[250px] h-screen fixed top-0 left-0
            overflow-y-auto border-r border-slate-700 z-40
            transition-transform duration-300 ease-in-out
            -translate-x-full lg:translate-x-0">

    <div class="flex items-center justify-between p-4 border-b border-slate-700">
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/logo.png') }}"
                 alt="Logo"
                 class="h-9 w-9 object-contain rounded-full bg-slate-700 p-1"
                 onerror="this.src='https://placehold.co/36x36/334155/white?text=BP'" />
            <div>
                <span class="text-sm font-bold text-white leading-tight">
                Municipal<br>Business Permit<br>& Licensing</span>
            
                <span class="text-[10px] bg-yellow-500 text-slate-900 px-1.5 py-0.5
                             rounded font-semibold">MANAGER</span>
            </div>
        </div>
        <button id="sidebar-close" class="text-slate-400 hover:text-white lg:hidden">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    @php
        use Illuminate\Support\Facades\Auth;
        // ALWAYS use guard('employee') — never auth()->user() here
        $employee = Auth::guard('employee')->user();
    @endphp

    {{-- <div class="p-4 border-b border-slate-700">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-full bg-yellow-500 flex items-center
                        justify-center text-slate-900 font-bold text-sm flex-shrink-0">
                {{ strtoupper(substr($employee->name, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-white truncate">
                    {{ $employee->name }}
                </p>
                <p class="text-xs text-slate-400">Manager</p>
            </div>
        </div>
    </div> --}}

    <nav class="p-3 space-y-4">
        <ul class="space-y-1">
            <li>
                <a href="{{ route('employee.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm
                          transition-all
                          {{ request()->routeIs('employee.dashboard')
                             ? 'bg-yellow-500 text-slate-900 font-semibold'
                             : 'text-slate-300 hover:text-white hover:bg-slate-700' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
        </ul>

        <div>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider
                       mb-1 px-2">Applications</p>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('employee.manager.applications.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm
                              transition-all
                              {{ request()->routeIs('employee.manager.applications.*')
                                 ? 'bg-yellow-500 text-slate-900 font-semibold'
                                 : 'text-slate-300 hover:text-white hover:bg-slate-700' }}">
                        <i class="bi bi-clipboard-check"></i> Review & Approve
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="absolute bottom-0 left-0 right-0 p-3 border-t border-slate-700">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg
                           text-sm text-slate-300 hover:text-red-400
                           hover:bg-slate-700 transition-all">
                <i class="bi bi-box-arrow-left"></i> Logout
            </button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar   = document.getElementById('sidebar');
        const overlay   = document.getElementById('sidebar-overlay');
        const toggleBtn = document.getElementById('sidebar-toggle');
        const closeBtn  = document.getElementById('sidebar-close');
        let isOpen = false;
        function openSidebar()  { sidebar.classList.remove('-translate-x-full'); sidebar.classList.add('translate-x-0'); overlay.classList.remove('hidden'); isOpen = true; }
        function closeSidebar() { sidebar.classList.add('-translate-x-full'); sidebar.classList.remove('translate-x-0'); overlay.classList.add('hidden'); isOpen = false; }
        if (toggleBtn) toggleBtn.addEventListener('click', () => isOpen ? closeSidebar() : openSidebar());
        if (closeBtn)  closeBtn.addEventListener('click', closeSidebar);
        if (overlay)   overlay.addEventListener('click', closeSidebar);
        document.addEventListener('keydown', e => { if (e.key === 'Escape' && isOpen) closeSidebar(); });
        window.addEventListener('resize',    () => { if (window.innerWidth >= 1024) closeSidebar(); });
    });
</script>