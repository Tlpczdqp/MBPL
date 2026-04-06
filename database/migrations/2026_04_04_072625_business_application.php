<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('business_applications', function (Blueprint $table) {
            $table->id();
            // Foreign key links to users table
            // When user is deleted, their applications are also deleted (cascade)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Who processed this application (employee)
            $table->foreignId('processed_by')->nullable()->constrained('employees')->onDelete('set null');

            // Transaction type: New or Renewal
            $table->enum('transact_type', ['New', 'Renewal'])->default('New');
            
            // Billing frequency
            $table->enum('billing_freq', ['Annually', 'Bi-Annually', 'Quarterly']);
            
            // Business registration type
            $table->enum('business_info', [
                'Sole Proprietorship',
                'One Person Corporation',
                'Partnerships',
                'Corporation',
                'Cooperation'
            ]);

            // Basic business info
            $table->string('business_name');
            $table->string('trade_name')->nullable();
            $table->string('reg_num'); // DTI/SEC number
            $table->string('business_tin');
            
            // Contact info
            $table->string('telephone_num')->nullable();
            $table->string('phone_number');
            $table->string('business_email');

            // Business Address (stored as separate fields for easy searching)
            $table->string('house_num')->nullable();
            $table->string('building_name')->nullable();
            $table->string('lot_num')->nullable();
            $table->string('block_num')->nullable();
            $table->string('street')->nullable();
            $table->string('barangay');
            $table->string('subdivision')->nullable();
            $table->string('city_muni');
            $table->string('province');
            $table->string('zip_code');

            // Owner info (Sole Proprietorship)
            $table->string('sp_owner_lname')->nullable();
            $table->string('sp_owner_fname')->nullable();
            $table->string('sp_owner_mname')->nullable();

            // Owner info (Corp/Coop/Partnership)
            $table->string('corp_owner_lname')->nullable();
            $table->string('corp_owner_fname')->nullable();
            $table->string('corp_owner_mname')->nullable();
            $table->enum('corp_location', ['Local', 'Foreign'])->nullable();

            // Business Activity
            $table->enum('business_act', [
                'Main Office',
                'Branch Office',
                'Admin Office Only',
                'Warehouse',
                'Others'
            ]);
            $table->string('business_act_other')->nullable(); // if "Others" is selected

            // Application status flow:
            // pending -> under_review -> approved / rejected
            // paid -> permit_issued
            $table->enum('status', [
                'pending',
                'under_review',
                'approved',
                'rejected',
                'paid',
                'permit_issued'
            ])->default('pending');

            $table->text('rejection_reason')->nullable(); // why was it rejected?
            $table->string('application_number')->unique(); // e.g. BPA-2024-00001
            $table->decimal('permit_fee', 10, 2)->nullable(); // fee set by manager/admin
            $table->timestamp('permit_issued_at')->nullable();
            $table->date('permit_valid_until')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_applications');
    }
};