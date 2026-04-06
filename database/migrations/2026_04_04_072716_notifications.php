<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            // A notification can belong to either a user OR an employee
            // We use a "morphable" approach with notifiable_type + notifiable_id
            // This means the same table works for both users and employees
            $table->string('notifiable_type'); // "App\Models\User" or "App\Models\Employee"
            $table->unsignedBigInteger('notifiable_id');
            $table->string('title');
            $table->text('message');
            $table->string('link')->nullable(); // where to go when clicked
            $table->boolean('is_read')->default(false);
            $table->timestamps();
            
            $table->index(['notifiable_type', 'notifiable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};