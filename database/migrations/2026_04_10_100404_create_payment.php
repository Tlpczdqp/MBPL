<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // ─── Relationships ────────────────────────────────────────────
            $table->foreignId('business_application_id')
                  ->constrained('business_applications')
                  ->cascadeOnDelete();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // ─── Payment Details ──────────────────────────────────────────
            $table->decimal('amount', 10, 2);
            $table->string('payment_method')->nullable();   // gcash | maya | card | grabpay
            $table->string('reference_number')->nullable()->unique();
            $table->string('proof_image')->nullable();      // manual upload (optional)

            // ─── Status ───────────────────────────────────────────────────
            $table->string('status')->default('pending');   // pending | paid | failed

            // ─── PayMongo Fields ──────────────────────────────────────────
            $table->string('paymongo_link_id')->nullable();
            $table->string('paymongo_payment_id')->nullable();
            $table->string('paymongo_checkout_url')->nullable();
            $table->string('paymongo_status')->nullable();  // unpaid | paid | failed

            // ─── Timestamps ───────────────────────────────────────────────
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};