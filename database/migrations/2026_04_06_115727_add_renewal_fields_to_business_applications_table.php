<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('business_applications', function (Blueprint $table) {
            //
            $table->string('application_type')->default('new')->after('application_number');
            $table->unsignedBigInteger('renewal_of')->nullable()->after('application_type');
            $table->integer('renewal_year')->nullable()->after('renewal_of');
            $table->text('remarks')->nullable();

            $table->foreign('renewal_of')
                ->references('id')
                ->on('business_applications')
                ->nullOnDelete();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_applications', function (Blueprint $table) {
            //
            $table->dropForeign(['renewal_of']);
            $table->dropColumn(['application_type', 'renewal_of', 'renewal_year', 'remarks']);
            $table->dropSoftDeletes();
        });
    }
};
