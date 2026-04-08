<?php
// This table stores regular users (citizens applying for permits)
// Why separate from employees? Different roles, different auth logic

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password')->nullable(); // nullable because social login has no password
            $table->string('phone')->nullable();
            $table->string('google_id')->nullable();   // for Google OAuth
            $table->string('facebook_id')->nullable(); // for Facebook OAuth
            $table->string('avatar')->nullable();       // profile photo from social login
            $table->string('otp')->nullable();          // 6-digit OTP code
            $table->timestamp('otp_expires_at')->nullable(); // OTP expiry time
            $table->boolean('email_verified')->default(false); // has the user verified their email?
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken(); // for "remember me" functionality
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};