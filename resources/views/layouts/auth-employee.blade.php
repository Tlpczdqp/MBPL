<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Employee Login') — Business Permit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="antialiased font-sans">

    {{-- Centered card layout —  simpler than the user login --}}
    <div class="flex min-h-screen flex-col items-center justify-center bg-slate-100 p-6">
        <div class="w-full max-w-md">

            <!-- Logo -->
            <div class="mb-6 flex flex-col items-center gap-2">
                <img src="{{ asset('images/logo.png') }}"
                     alt="Logo"
                     class="h-16 w-16 object-contain"
                     onerror="this.style.display='none'" />
                <p class="text-sm font-medium text-slate-600">Employee Portal</p>
            </div>

            <!-- Card -->
            <div class="rounded-2xl bg-white shadow-md border border-slate-200">
                <div class="px-8 pt-8 pb-0 text-center">
                    <h1 class="text-xl font-semibold text-slate-900">@yield('title')</h1>
                    <p class="mt-1 text-sm text-slate-500">@yield('description')</p>
                </div>
                <div class="px-8 py-8">
                    @yield('content')
                </div>
            </div>

        </div>
    </div>
</body>
</html>