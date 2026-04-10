<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {

            // ✅ Only add columns that don't exist yet
            if (! Schema::hasColumn('payments', 'paymongo_checkout_id')) {
                $table->string('paymongo_checkout_id')->nullable()->after('user_id');
            }

            if (! Schema::hasColumn('payments', 'paymongo_payment_id')) {
                $table->string('paymongo_payment_id')->nullable()->after('paymongo_checkout_id');
            }

            if (! Schema::hasColumn('payments', 'checkout_url')) {
                $table->string('checkout_url')->nullable()->after('paymongo_payment_id');
            }

            if (! Schema::hasColumn('payments', 'currency')) {
                $table->string('currency', 3)->default('PHP')->after('amount');
            }

            if (! Schema::hasColumn('payments', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('currency');
            }

            if (! Schema::hasColumn('payments', 'reference_number')) {
                $table->string('reference_number')->nullable()->after('payment_method');
            }

            if (! Schema::hasColumn('payments', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('reference_number');
            }

            if (! Schema::hasColumn('payments', 'status')) {
                $table->string('status')->default('pending')->after('paid_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'paymongo_checkout_id',
                'paymongo_payment_id',
                'checkout_url',
                'currency',
                'payment_method',
                'reference_number',
                'paid_at',
                'status',
            ]);
        });
    }
};