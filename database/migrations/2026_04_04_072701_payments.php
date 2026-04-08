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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['gcash', 'paymaya', 'bank_transfer']);
            $table->string('reference_number'); // payment reference from the user
            $table->string('proof_image')->nullable(); // screenshot of payment
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};