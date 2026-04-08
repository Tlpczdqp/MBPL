<?php

return [

    'defaults' => [
        'guard'     => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        // Default guard — used by auth()->user() and Auth::user()
        // This returns a USER model
        'web' => [
            'driver'   => 'session',
            'provider' => 'users',
        ],

        // Employee guard — used by Auth::guard('employee')->user()
        // This returns an EMPLOYEE model
        // This is a SEPARATE guard from 'web'
        'employee' => [
            'driver'   => 'session',
            'provider' => 'employees',
        ],
    ],

    'providers' => [
        // Links 'web' guard to User model
        'users' => [
            'driver' => 'eloquent',
            'model'  => App\Models\User::class,
        ],

        // Links 'employee' guard to Employee model
        // This is what makes Auth::guard('employee')->user() return Employee
        'employees' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Employee::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table'    => 'password_reset_tokens',
            'expire'   => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];