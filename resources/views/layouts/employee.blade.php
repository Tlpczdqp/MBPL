<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Employee Portal') — Business Permit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
          rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-100 overflow-x-hidden" x-data="{ sidebarOpen: false }">

@php
    // ALWAYS use Auth::guard('employee') to get the Employee model
    // NEVER use auth()->user() here — that returns User model
    // auth()->user() uses the default 'web' guard → returns User → isStaff() fails

    use Illuminate\Support\Facades\Auth;

    $employee = Auth::guard('employee')->user();
@endphp

{{--
    Check the employee role and include the correct sidebar
    isAdmin(), isManager(), isStaff() are only valid on Employee model
    $employee here is guaranteed to be an Employee because we used guard('employee')
--}}
@if($employee && $employee->isAdmin())
    @include('partials.employee-sidebar-admin')

@elseif($employee && $employee->isManager())
    @include('partials.employee-sidebar-manager')

@elseif($employee && $employee->isStaff())
    @include('partials.employee-sidebar-staff')

@else
    {{-- Fallback: show staff sidebar if role is unknown --}}
    @include('partials.employee-sidebar-staff')
@endif

<div class="min-h-screen transition-all duration-300 lg:ml-[250px]">

    @include('partials.employee-navbar')

    {{-- Flash Messages --}}
    <div class="px-5 pt-4">
        @if(session('success'))
            <div class="flex items-center gap-2 bg-green-100 border border-green-300
                        text-green-800 text-sm rounded-lg px-4 py-3 mb-4"
                 x-data="{ show: true }"
                 x-show="show">
                <i class="bi bi-check-circle-fill flex-shrink-0"></i>
                <span>{{ session('success') }}</span>
                <button @click="show = false" class="ml-auto">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="flex items-center gap-2 bg-red-100 border border-red-300
                        text-red-800 text-sm rounded-lg px-4 py-3 mb-4"
                 x-data="{ show: true }"
                 x-show="show">
                <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
                <span>{{ session('error') }}</span>
                <button @click="show = false" class="ml-auto">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        @endif
    </div>

    <div class="p-5" >
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>