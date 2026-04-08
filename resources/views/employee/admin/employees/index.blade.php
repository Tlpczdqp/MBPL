@extends('layouts.employee')

@section('title', 'Manage Employees')

@section('content')

{{-- Page Header --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Manage Employees</h1>
        <p class="text-sm text-slate-500 mt-1">View, add, and manage employee accounts.</p>
    </div>
    <a href="{{ route('employee.admin.employees.create') }}"
       class="inline-flex items-center gap-2 bg-[#1e2a4a] hover:bg-[#16213a] text-white
              text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors duration-200 shadow">
        <i class="bi bi-person-plus-fill"></i>
        Add Employee
    </a>
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
            <p class="text-xs text-slate-500">Total Employees</p>
        </div>
    </div>

    {{-- Staff --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
            <i class="bi bi-person-fill text-green-600 text-lg"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800">{{ $counts['staff'] }}</p>
            <p class="text-xs text-slate-500">Staff</p>
        </div>
    </div>

    {{-- Manager --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
            <i class="bi bi-person-gear text-amber-600 text-lg"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800">{{ $counts['manager'] }}</p>
            <p class="text-xs text-slate-500">Managers</p>
        </div>
    </div>

    {{-- Admin --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
            <i class="bi bi-shield-fill text-red-600 text-lg"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800">{{ $counts['admin'] }}</p>
            <p class="text-xs text-slate-500">Admins</p>
        </div>
    </div>

</div>

{{-- Filters --}}
<div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 mb-4">
    <form method="GET" action="{{ route('employee.admin.employees.index') }}"
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
                placeholder="Search by name, email, or employee ID..."
                class="w-full pl-9 pr-4 py-2.5 border border-slate-300 rounded-lg text-sm
                       focus:outline-none focus:ring-2 focus:ring-[#1e2a4a]"
            />
        </div>

        {{-- Role Filter --}}
        <select name="role"
                class="px-4 py-2.5 border border-slate-300 rounded-lg text-sm text-slate-700
                       focus:outline-none focus:ring-2 focus:ring-[#1e2a4a] bg-white">
            <option value="">All Roles</option>
            <option value="staff"   {{ request('role') === 'staff'   ? 'selected' : '' }}>Staff</option>
            <option value="manager" {{ request('role') === 'manager' ? 'selected' : '' }}>Manager</option>
            <option value="admin"   {{ request('role') === 'admin'   ? 'selected' : '' }}>Admin</option>
        </select>

        {{-- Department Filter --}}
        <select name="department"
                class="px-4 py-2.5 border border-slate-300 rounded-lg text-sm text-slate-700
                       focus:outline-none focus:ring-2 focus:ring-[#1e2a4a] bg-white">
            <option value="">All Departments</option>
            @foreach($departments as $dept)
                <option value="{{ $dept }}" {{ request('department') === $dept ? 'selected' : '' }}>
                    {{ $dept }}
                </option>
            @endforeach
        </select>

        {{-- Buttons --}}
        <button type="submit"
                class="flex items-center gap-2 bg-[#1e2a4a] hover:bg-[#16213a] text-white
                       text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
            <i class="bi bi-funnel"></i> Filter
        </button>

        @if(request()->hasAny(['search', 'role', 'department']))
            <a href="{{ route('employee.admin.employees.index') }}"
               class="flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-600
                      text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                <i class="bi bi-x"></i> Clear
            </a>
        @endif

    </form>
</div>

{{-- Employee Table --}}
<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">

    @if($employees->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                <i class="bi bi-people text-slate-400 text-2xl"></i>
            </div>
            <p class="text-slate-600 font-medium">No employees found</p>
            <p class="text-slate-400 text-sm mt-1">Try adjusting your search or filters.</p>
            <a href="{{ route('employee.admin.employees.create') }}"
               class="mt-4 inline-flex items-center gap-2 bg-[#1e2a4a] text-white
                      text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-[#16213a] transition-colors">
                <i class="bi bi-person-plus-fill"></i> Add First Employee
            </a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">

                {{-- Table Head --}}
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide px-5 py-3">
                            Employee
                        </th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide px-5 py-3">
                            Role
                        </th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide px-5 py-3 hidden md:table-cell">
                            Department
                        </th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide px-5 py-3 hidden lg:table-cell">
                            Employee ID
                        </th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide px-5 py-3 hidden lg:table-cell">
                            Joined
                        </th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide px-5 py-3">
                            Status
                        </th>
                        <th class="text-right text-xs font-semibold text-slate-500 uppercase tracking-wide px-5 py-3">
                            Actions
                        </th>
                    </tr>
                </thead>

                {{-- Table Body --}}
                <tbody class="divide-y divide-slate-100">
                    @foreach($employees as $employee)
                        <tr class="hover:bg-slate-50 transition-colors"
                            x-data="{ deleteModal: false, resetModal: false }">

                            {{-- Employee Name + Email --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center
                                                text-white text-sm font-bold flex-shrink-0
                                                {{ $employee->role === 'admin'   ? 'bg-red-500' :
                                                   ($employee->role === 'manager' ? 'bg-amber-500' : 'bg-green-500') }}">
                                        {{ strtoupper(substr($employee->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $employee->name }}</p>
                                        <p class="text-xs text-slate-400">{{ $employee->email }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Role Badge --}}
                            <td class="px-5 py-4">
                                @php
                                    $roleConfig = [
                                        'staff'   => ['bg-green-100 text-green-700',  'bi-person-fill',  'Staff'],
                                        'manager' => ['bg-amber-100 text-amber-700',  'bi-person-gear',  'Manager'],
                                        'admin'   => ['bg-red-100 text-red-700',      'bi-shield-fill',  'Admin'],
                                    ];
                                    [$cls, $icon, $label] = $roleConfig[$employee->role] ?? ['bg-slate-100 text-slate-600', 'bi-person', ucfirst($employee->role)];
                                @endphp
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full {{ $cls }}">
                                    <i class="bi {{ $icon }}"></i> {{ $label }}
                                </span>
                            </td>

                            {{-- Department --}}
                            <td class="px-5 py-4 hidden md:table-cell">
                                <span class="text-slate-600">
                                    {{ $employee->department ?? '—' }}
                                </span>
                            </td>

                            {{-- Employee ID --}}
                            <td class="px-5 py-4 hidden lg:table-cell">
                                <span class="font-mono text-xs text-slate-500 bg-slate-100 px-2 py-1 rounded">
                                    {{ $employee->employee_id ?? '—' }}
                                </span>
                            </td>

                            {{-- Joined --}}
                            <td class="px-5 py-4 hidden lg:table-cell text-slate-500 text-xs">
                                {{ $employee->created_at->format('M d, Y') }}
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-4">
                                @if($employee->deleted_at)
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-500">
                                        <i class="bi bi-slash-circle"></i> Inactive
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-green-100 text-green-700">
                                        <i class="bi bi-check-circle-fill"></i> Active
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">

                                    {{-- Edit --}}
                                    <a href="{{ route('employee.admin.employees.edit', $employee) }}"
                                       class="w-8 h-8 flex items-center justify-center rounded-lg
                                              bg-slate-100 hover:bg-[#1e2a4a] text-slate-500 hover:text-white
                                              transition-colors duration-200"
                                       title="Edit">
                                        <i class="bi bi-pencil text-xs"></i>
                                    </a>

                                    {{-- Reset Password --}}
                                    <button type="button"
                                            @click="resetModal = true"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg
                                                   bg-slate-100 hover:bg-amber-500 text-slate-500 hover:text-white
                                                   transition-colors duration-200"
                                            title="Reset Password">
                                        <i class="bi bi-key text-xs"></i>
                                    </button>

                                    {{-- Deactivate / Restore --}}
                                    @if($employee->deleted_at)
                                        <form action="{{ route('employee.admin.employees.restore', $employee->id) }}" method="POST">
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
                                                @click="deleteModal = true"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg
                                                       bg-slate-100 hover:bg-red-500 text-slate-500 hover:text-white
                                                       transition-colors duration-200"
                                                title="Deactivate">
                                            <i class="bi bi-person-slash text-xs"></i>
                                        </button>
                                    @endif

                                </div>

                                {{-- ── Deactivate Modal ── --}}
                                <div x-show="deleteModal" x-cloak
                                     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0"
                                     x-transition:enter-end="opacity-100">
                                    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6"
                                         @click.outside="deleteModal = false"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100">
                                        <div class="flex justify-center mb-4">
                                            <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center">
                                                <i class="bi bi-person-slash text-red-600 text-2xl"></i>
                                            </div>
                                        </div>
                                        <h3 class="text-center text-base font-bold text-slate-800 mb-1">Deactivate Employee</h3>
                                        <p class="text-center text-sm text-slate-500 mb-5">
                                            Are you sure you want to deactivate
                                            <strong>{{ $employee->name }}</strong>?
                                            They will lose access immediately.
                                        </p>
                                        <div class="flex gap-3">
                                            <button @click="deleteModal = false"
                                                    class="flex-1 py-2.5 rounded-lg text-sm font-semibold
                                                           bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors">
                                                Cancel
                                            </button>
                                            <form action="{{ route('employee.admin.employees.destroy', $employee) }}"
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

                                {{-- ── Reset Password Modal ── --}}
                                <div x-show="resetModal" x-cloak
                                     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0"
                                     x-transition:enter-end="opacity-100">
                                    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6"
                                         @click.outside="resetModal = false"
                                         x-data="{ showPass: false, showConfirm: false }"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100">
                                        <div class="flex justify-center mb-4">
                                            <div class="w-14 h-14 bg-amber-100 rounded-full flex items-center justify-center">
                                                <i class="bi bi-key-fill text-amber-600 text-2xl"></i>
                                            </div>
                                        </div>
                                        <h3 class="text-center text-base font-bold text-slate-800 mb-1">Reset Password</h3>
                                        <p class="text-center text-sm text-slate-500 mb-5">
                                            Set a new password for <strong>{{ $employee->name }}</strong>.
                                        </p>
                                        <form action="{{ route('employee.admin.employees.reset-password', $employee) }}"
                                              method="POST" class="space-y-4">
                                            @csrf
                                            @method('PUT')

                                            {{-- New Password --}}
                                            <div class="relative">
                                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                                    <i class="bi bi-lock"></i>
                                                </span>
                                                <input :type="showPass ? 'text' : 'password'"
                                                       name="password"
                                                       placeholder="New password (min. 8 chars)"
                                                       class="w-full pl-9 pr-10 py-2.5 border border-slate-300 rounded-lg
                                                              text-sm focus:outline-none focus:ring-2 focus:ring-[#1e2a4a]" />
                                                <button type="button" @click="showPass = !showPass"
                                                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400">
                                                    <i :class="showPass ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                                                </button>
                                            </div>

                                            {{-- Confirm Password --}}
                                            <div class="relative">
                                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                                    <i class="bi bi-lock-fill"></i>
                                                </span>
                                                <input :type="showConfirm ? 'text' : 'password'"
                                                       name="password_confirmation"
                                                       placeholder="Confirm new password"
                                                       class="w-full pl-9 pr-10 py-2.5 border border-slate-300 rounded-lg
                                                              text-sm focus:outline-none focus:ring-2 focus:ring-[#1e2a4a]" />
                                                <button type="button" @click="showConfirm = !showConfirm"
                                                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400">
                                                    <i :class="showConfirm ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                                                </button>
                                            </div>

                                            <div class="flex gap-3 pt-1">
                                                <button type="button"
                                                        @click="resetModal = false"
                                                        class="flex-1 py-2.5 rounded-lg text-sm font-semibold
                                                               bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors">
                                                    Cancel
                                                </button>
                                                <button type="submit"
                                                        class="flex-1 py-2.5 rounded-lg text-sm font-semibold
                                                               bg-amber-500 hover:bg-amber-600 text-white transition-colors shadow">
                                                    Reset Password
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($employees->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $employees->links() }}
            </div>
        @endif

    @endif
</div>

@endsection