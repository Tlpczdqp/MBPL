@extends('layouts.employee')
@section('title', 'Create Employee Account')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('employee.admin.employees.index') }}"
            class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-slate-200
              hover:bg-slate-50 text-slate-500 transition-colors shadow-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Create Employee Account</h1>
            <p class="text-sm text-slate-500 mt-0.5">Add a new employee to the system</p>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3 mb-5">
            <p class="font-semibold mb-1">Please fix the following errors:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <form method="POST"
              action="{{ route('employee.admin.employees.store') }}"
              class="space-y-5">
            @csrf

            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">
                    Full Name <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i class="bi bi-person"></i>
                    </span>
                    <input id="name"
                           type="text"
                           name="name"
                           value="{{ old('name') }}"
                           placeholder="Employee full name"
                           class="w-full pl-9 pr-4 py-2.5 text-sm rounded-lg border
                                  {{ $errors->has('name') ? 'border-red-400' : 'border-slate-300' }}
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />
                </div>
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">
                    Email Address <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input id="email"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="employee@office.gov.ph"
                           class="w-full pl-9 pr-4 py-2.5 text-sm rounded-lg border
                                  {{ $errors->has('email') ? 'border-red-400' : 'border-slate-300' }}
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />
                </div>
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Role --}}
            {{-- x-data lets us show a description of the selected role --}}
            <div x-data="{ role: '{{ old('role', 'staff') }}' }">
                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Employee Role <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-3 gap-3">

                    {{-- Staff --}}
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="staff"
                               x-model="role" class="sr-only" />
                        <div :class="role === 'staff'
                                        ? 'border-green-500 bg-green-50 ring-2 ring-green-500'
                                        : 'border-slate-200 hover:border-slate-300'"
                             class="border-2 rounded-xl p-3 text-center transition-all">
                            <i class="bi bi-person text-2xl text-green-600 block mb-1"></i>
                            <p class="text-xs font-bold text-slate-800">Staff</p>
                        </div>
                    </label>

                    {{-- Manager --}}
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="manager"
                               x-model="role" class="sr-only" />
                        <div :class="role === 'manager'
                                        ? 'border-yellow-500 bg-yellow-50 ring-2 ring-yellow-500'
                                        : 'border-slate-200 hover:border-slate-300'"
                             class="border-2 rounded-xl p-3 text-center transition-all">
                            <i class="bi bi-person-gear text-2xl text-yellow-600 block mb-1"></i>
                            <p class="text-xs font-bold text-slate-800">Manager</p>
                        </div>
                    </label>

                    {{-- Admin --}}
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="admin"
                               x-model="role" class="sr-only" />
                        <div :class="role === 'admin'
                                        ? 'border-red-500 bg-red-50 ring-2 ring-red-500'
                                        : 'border-slate-200 hover:border-slate-300'"
                             class="border-2 rounded-xl p-3 text-center transition-all">
                            <i class="bi bi-shield-check text-2xl text-red-600 block mb-1"></i>
                            <p class="text-xs font-bold text-slate-800">Admin</p>
                        </div>
                    </label>

                </div>

                {{-- Role description --}}
                <div class="mt-2 text-xs text-slate-500 bg-slate-50 rounded-lg p-3">
                    <span x-show="role === 'staff'">
                        <strong>Staff:</strong> Can view and process applications,
                        mark them under review.
                    </span>
                    <span x-show="role === 'manager'" x-cloak>
                        <strong>Manager:</strong> Can approve/reject applications,
                        verify payments, and issue permits.
                    </span>
                    <span x-show="role === 'admin'" x-cloak>
                        <strong>Admin:</strong> Full access — all manager permissions
                        plus employee account management.
                    </span>
                </div>

                @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Employee ID --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="employee_id" class="block text-sm font-medium text-slate-700 mb-1">
                        Employee ID
                        <span class="text-slate-400 font-normal">(optional)</span>
                    </label>
                    <input id="employee_id"
                           type="text"
                           name="employee_id"
                           value="{{ old('employee_id') }}"
                           placeholder="e.g. EMP-001"
                           class="w-full px-3 py-2.5 text-sm rounded-lg border border-slate-300
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />
                    @error('employee_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="department" class="block text-sm font-medium text-slate-700 mb-1">
                        Department
                        <span class="text-slate-400 font-normal">(optional)</span>
                    </label>
                    <input id="department"
                           type="text"
                           name="department"
                           value="{{ old('department') }}"
                           placeholder="e.g. Licensing"
                           class="w-full px-3 py-2.5 text-sm rounded-lg border border-slate-300
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />
                </div>
            </div>

            {{-- Password --}}
            <div x-data="{ showPass: false }">
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">
                    Password <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input id="password"
                           :type="showPass ? 'text' : 'password'"
                           name="password"
                           placeholder="Min. 8 characters"
                           class="w-full pl-9 pr-10 py-2.5 text-sm rounded-lg border
                                  {{ $errors->has('password') ? 'border-red-400' : 'border-slate-300' }}
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />
                    <button type="button" @click="showPass = !showPass"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400">
                        <i :class="showPass ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                    </button>
                </div>
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Confirm Password --}}
            <div x-data="{ showPass: false }">
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">
                    Confirm Password <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i class="bi bi-lock-fill"></i>
                    </span>
                    <input id="password_confirmation"
                           :type="showPass ? 'text' : 'password'"
                           name="password_confirmation"
                           placeholder="Repeat password"
                           class="w-full pl-9 pr-10 py-2.5 text-sm rounded-lg border border-slate-300
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />
                    <button type="button" @click="showPass = !showPass"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400">
                        <i :class="showPass ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                    </button>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-3 pt-2 border-t border-slate-100">
                <a href="{{ route('employee.admin.employees.index') }}"
                   class="flex-1 py-2.5 text-center text-sm font-semibold border border-slate-300
                          text-slate-700 rounded-lg hover:bg-slate-50 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="flex-1 py-2.5 bg-slate-900 hover:bg-slate-700 text-white
                               text-sm font-semibold rounded-lg transition-colors">
                    <i class="bi bi-person-plus mr-1"></i>
                    Create Account
                </button>
            </div>

        </form>
    </div>

</div>
@endsection