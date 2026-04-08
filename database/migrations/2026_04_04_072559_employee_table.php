<?php
// Employees are system users (staff, managers, admins)
// They are NOT mixed with regular users for security clarity

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            // role: 'admin', 'manager', 'staff'
            // Admin: full access, creates employee accounts
            // Manager: can approve/reject, sees reports
            // Staff: processes applications
            $table->enum('role', ['admin', 'manager', 'staff'])->default('staff');
            $table->string('employee_id')->unique()->nullable(); // employee badge ID
            $table->string('department')->nullable();
            $table->string('avatar')->nullable();
            $table->boolean('is_active')->default(true); // can admin deactivate employee?
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};