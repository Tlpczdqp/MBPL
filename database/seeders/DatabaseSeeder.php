<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Employee::create([
            'name'        => 'System Administrator',
            'email'       => 'admin@mbpl.com',
            'password'    => Hash::make('Admin@12345'),
            'role'        => 'admin',
            'employee_id' => 'EMP-001',
            'department'  => 'IT Administration',
            'is_active'   => true,
        ]);

        // Create a default manager
        Employee::create([
            'name'        => 'Manager',
            'email'       => 'manager@mbpl.com',
            'password'    => Hash::make('Manager@12345'),
            'role'        => 'manager',
            'employee_id' => 'EMP-002',
            'department'  => 'Business Licensing',
            'is_active'   => true,
        ]);

        // Create a default staff
        Employee::create([
            'name'        => 'Staff',
            'email'       => 'staff@mbpl.com',
            'password'    => Hash::make('Staff@12345'),
            'role'        => 'staff',
            'employee_id' => 'EMP-003',
            'department'  => 'Application Processing',
            'is_active'   => true,
        ]);
    }
}
