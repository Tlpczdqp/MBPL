<?php
// Stores the 4 uploaded documents separately
// Why separate table? One application has MANY documents
// This follows the "One to Many" database relationship

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('business_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_application_id')
                  ->constrained('business_applications')
                  ->onDelete('cascade'); // delete files when application deleted
            
            // What type of document is this?
            $table->enum('document_type', [
                'dti_sec_certificate',
                'valid_id',
                'business_photo',
                'business_sketch'
            ]);
            
            $table->string('file_path');   // where the file is stored in storage/
            $table->string('file_name');   // original filename
            $table->string('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_documents');
    }
};