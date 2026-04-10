@extends('layouts.employee')

@section('title', 'Edit Employee')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('employee.admin.employees.index') }}"
            class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-slate-200
              hover:bg-slate-50 text-slate-500 transition-colors shadow-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Employee</h1>
            <p class="text-sm text-slate-500 mt-0.5">Update details for {{ $employee->name }}.</p>
        </div>
    </div>

    <div class="max-w-2xl" x-data="{
        role: '{{ old('role', $employee->role) }}',
        roles: {
            staff: { icon: 'bi-person-fill', color: 'text-green-600', bg: 'bg-green-50', border: 'border-green-500', desc: 'Can view and process applications, mark them under review.' },
            manager: { icon: 'bi-person-gear', color: 'text-amber-600', bg: 'bg-amber-50', border: 'border-amber-500', desc: 'Can manage staff, approve or reject permit applications.' },
            admin: { icon: 'bi-shield-fill', color: 'text-red-600', bg: 'bg-red-50', border: 'border-red-500', desc: 'Full system access including employee and settings management.' },
        }
    }">

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

            <form action="{{ route('employee.admin.employees.update', $employee) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Name --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <i class="bi bi-person"></i>
                        </span>
                        <input type="text" name="name" value="{{ old('name', $employee->name) }}"
                            class="w-full pl-9 pr-4 py-2.5 border rounded-lg text-sm text-slate-700
                                  focus:outline-none focus:ring-2 focus:ring-[#1e2a4a]
                                  {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}" />
                    </div>
                    @error('name')
                        <p class="text-xs text-red-500 mt-1 flex items-center gap-1">
                            <i class="bi bi-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Email (readonly) --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Email Address
                        <span class="ml-1 text-xs text-slate-400 font-normal">
                            <i class="bi bi-lock-fill"></i> Cannot be changed
                        </span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input type="email" value="{{ $employee->email }}" readonly
                            class="w-full pl-9 pr-4 py-2.5 border border-slate-200 rounded-lg text-sm
                                  text-slate-400 bg-slate-50 cursor-not-allowed" />
                    </div>
                    <input type="hidden" name="email" value="{{ $employee->email }}" />
                </div>

                {{-- Role --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Employee Role <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-3 gap-3 mb-3">
                        @foreach (['staff', 'manager', 'admin'] as $r)
                            <button type="button" @click="role = '{{ $r }}'"
                                :class="role === '{{ $r }}'
                                    ?
                                    roles['{{ $r }}'].border + ' ' + roles['{{ $r }}'].bg +
                                    ' border-2' :
                                    'border-slate-200 bg-white border hover:border-slate-300'"
                                class="flex flex-col items-center gap-2 p-4 rounded-xl transition-all cursor-pointer">
                                <i class="bi text-2xl"
                                    :class="[roles['{{ $r }}'].icon, role === '{{ $r }}' ? roles[
                                        '{{ $r }}'].color : 'text-slate-400']"></i>
                                <span class="text-sm font-semibold"
                                    :class="role === '{{ $r }}' ? roles['{{ $r }}'].color :
                                        'text-slate-500'">
                                    {{ ucfirst($r) }}
                                </span>
                            </button>
                        @endforeach
                    </div>
                    <div class="flex items-start gap-2 text-xs px-3 py-2 rounded-lg bg-slate-50 border border-slate-200">
                        <i class="bi bi-info-circle text-slate-400 mt-0.5 flex-shrink-0"></i>
                        <span class="text-slate-600" x-text="roles[role].desc"></span>
                    </div>
                    <input type="hidden" name="role" x-model="role" />
                </div>

                {{-- Employee ID + Department --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Employee ID</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <i class="bi bi-badge-id"></i>
                            </span>
                            <input type="text" name="employee_id"
                                value="{{ old('employee_id', $employee->employee_id) }}" placeholder="e.g. EMP-001"
                                class="w-full pl-9 pr-4 py-2.5 border rounded-lg text-sm text-slate-700
                                      focus:outline-none focus:ring-2 focus:ring-[#1e2a4a]
                                      {{ $errors->has('employee_id') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}" />
                        </div>
                        @error('employee_id')
                            <p class="text-xs text-red-500 mt-1 flex items-center gap-1">
                                <i class="bi bi-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Department</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <i class="bi bi-building"></i>
                            </span>
                            <input type="text" name="department" value="{{ old('department', $employee->department) }}"
                                placeholder="e.g. Licensing"
                                class="w-full pl-9 pr-4 py-2.5 border rounded-lg text-sm text-slate-700
                                      focus:outline-none focus:ring-2 focus:ring-[#1e2a4a]
                                      {{ $errors->has('department') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}" />
                        </div>
                    </div>
                </div>

                {{-- Phone --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Phone Number</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <i class="bi bi-telephone"></i>
                        </span>
                        <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}"
                            placeholder="+63 912 345 6789"
                            class="w-full pl-9 pr-4 py-2.5 border border-slate-300 rounded-lg text-sm text-slate-700
                                  focus:outline-none focus:ring-2 focus:ring-[#1e2a4a]" />
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                        class="flex items-center gap-2 bg-[#1e2a4a] hover:bg-[#16213a] text-white
                               text-sm font-semibold px-6 py-2.5 rounded-lg transition-colors shadow">
                        <i class="bi bi-floppy"></i> Save Changes
                    </button>
                    <a href="{{ route('employee.admin.employees.index') }}"
                        class="flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700
                          text-sm font-semibold px-6 py-2.5 rounded-lg transition-colors">
                        Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
