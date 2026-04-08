<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Dashboard') — Business Permit</title>

    <!-- Tailwind CSS CDN (beginner-friendly, no build step needed) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Bootstrap Icons (for bi-* icons used throughout) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

    <!-- Alpine.js (for dropdown menus, toggles, x-data, x-show, etc.) -->
    <!-- Alpine.js lets us write reactive behavior without a full JS framework -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* Hide Alpine elements before JS loads (prevents flash of content) */
        [x-cloak] { display: none !important; }
    </style>
</head>

<!-- x-data on body makes Alpine available to all children -->
<body class="bg-slate-100 overflow-x-hidden" x-data="{ sidebarOpen: false }">

    {{-- Include the user sidebar partial --}}
    @include('partials.user-sidebar')

    {{-- Main content wrapper: pushed right by 250px on large screens (sidebar width) --}}
    {{-- On mobile it's full width (sidebar overlays) --}}
    <div class="min-h-screen transition-all duration-300 lg:ml-[250px]">

        {{-- Include the user navbar partial --}}
        @include('partials.user-navbar')

        {{-- Flash messages (success/error notifications) --}}
        <div class="px-5 pt-4">
            @if(session('success'))
                <div class="flex items-center gap-2 bg-green-100 border border-green-300 text-green-800
                            text-sm rounded-lg px-4 py-3 mb-4"
                     x-data="{ show: true }" x-show="show">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>{{ session('success') }}</span>
                    <button @click="show = false" class="ml-auto text-green-600 hover:text-green-800">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="flex items-center gap-2 bg-red-100 border border-red-300 text-red-800
                            text-sm rounded-lg px-4 py-3 mb-4"
                     x-data="{ show: true }" x-show="show">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <span>{{ session('error') }}</span>
                    <button @click="show = false" class="ml-auto text-red-600 hover:text-red-800">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            @endif
        </div>

        {{-- Page content injected here --}}
        <div class="p-5">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS (for any Bootstrap components if needed) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>