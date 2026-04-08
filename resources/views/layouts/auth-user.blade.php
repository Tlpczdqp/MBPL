<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Login') — Business Permit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-white">

    {{--
        Two-column layout on large screens:
        LEFT  = dark decorative panel with logo
        RIGHT = the actual form

        On mobile: only the right column shows (left is hidden with 'hidden lg:flex')
    --}}
    <div class="relative grid min-h-dvh items-center justify-center
                lg:max-w-none lg:grid-cols-2 lg:px-0">

        <!-- LEFT PANEL: Dark background, logo, decorative text -->
        <div class="relative hidden h-full flex-col bg-zinc-900 p-10 text-white lg:flex">
            <!-- Logo top left -->
            <a href="{{ url('/') }}" class="relative z-20 flex items-center text-lg font-medium">
                {{-- Logo image from public folder --}}
                <img src="{{ asset('images/logo.png') }}"
                     alt="Logo"
                     class="mr-3 h-10 w-10 object-contain"
                     onerror="this.style.display='none'" />
                <span class="font-bold text-white">Municipal Business Permit</span>
            </a>

            <!-- Centered quote / description -->
            <div class="relative z-20 mt-auto">
                <blockquote class="space-y-2">
                    <p class="text-lg text-slate-300">
                        "Streamlining business permit applications for our community."
                    </p>
                    <footer class="text-sm text-slate-400">
                        Municipal Business Permit and Licensing Office
                    </footer>
                </blockquote>
            </div>
        </div>

        <!-- RIGHT PANEL: The form -->
        <div class="px-8 py-12 sm:px-16 lg:px-24">

            <!-- Mobile logo (shown only on small screens) -->
            <div class="mb-6 flex justify-center lg:hidden">
                <img src="{{ asset('images/logo.png') }}"
                     alt="Logo"
                     class="h-16 w-16 object-contain" />
            </div>

            <div class="mx-auto w-full max-w-sm">
                @yield('content')
            </div>
        </div>
    </div>

    {{-- Prevent browser back button from loading cached login page --}}
    <script>
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) { window.location.reload(); }
        });
    </script>
</body>
</html>