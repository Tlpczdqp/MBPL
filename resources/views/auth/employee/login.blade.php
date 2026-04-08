@extends('layouts.auth-employee')
@section('title', 'Employee Sign In')
@section('description', 'Sign in to access the employee portal')

@section('content')

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm
                    rounded-lg px-4 py-3 mb-5">
            {{ $errors->first() }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm
                    rounded-lg px-4 py-3 mb-5">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('employee.login.submit') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 mb-1">
                Email Address
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
                       class="w-full pl-9 pr-4 py-2.5 text-sm rounded-lg border border-slate-300
                              text-slate-900 placeholder:text-slate-400
                              focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />
            </div>
        </div>

        <div x-data="{ showPass: false }">
            <label for="password" class="block text-sm font-medium text-slate-700 mb-1">
                Password
            </label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <i class="bi bi-lock"></i>
                </span>
                <input id="password"
                       :type="showPass ? 'text' : 'password'"
                       name="password"
                       placeholder="••••••••"
                       class="w-full pl-9 pr-10 py-2.5 text-sm rounded-lg border border-slate-300
                              text-slate-900 placeholder:text-slate-400
                              focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />
                <button type="button" @click="showPass = !showPass"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400">
                    <i :class="showPass ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                </button>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" id="remember" name="remember"
                   class="h-4 w-4 rounded border-slate-300 text-blue-600" />
            <label for="remember" class="text-sm text-slate-600">Keep me signed in</label>
        </div>

        <button type="submit"
                class="w-full py-2.5 px-4 bg-slate-900 hover:bg-slate-700 text-white
                       text-sm font-semibold rounded-lg transition-colors">
            Sign In to Portal
        </button>
    </form>

    <p class="text-center text-xs text-slate-400 mt-6">
        Are you a citizen?
        <a href="{{ route('user.login') }}" class="text-slate-600 hover:underline">
            Go to User Login →
        </a>
    </p>

@endsection