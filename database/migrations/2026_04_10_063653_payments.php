// database/migrations/xxxx_create_payments_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_application_id')
                  ->constrained('business_applications')
                  ->onDelete('cascade');

            $table->string('paymongo_checkout_id')->nullable();
            $table->string('paymongo_payment_id')->nullable();

            // pending | paid | failed | expired
            $table->enum('status', ['pending', 'paid', 'failed', 'expired'])
                  ->default('pending');

            $table->decimal('amount', 10, 2);
            $table->string('payment_method')->nullable(); // gcash, card, paymaya
            $table->string('checkout_url')->nullable();
            $table->json('paymongo_response')->nullable(); // raw response for reference
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};