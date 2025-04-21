<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    
public function up()
{
    Schema::create('reopen_requests', function (Blueprint $table) {
        $table->id();
        $table->foreignId('student_id')->constrained()->onDelete('cascade');
        $table->foreignId('supervisor_id')->constrained()->onDelete('cascade');
        $table->date('reopened_date');
        $table->timestamp('expires_at');
        $table->text('message')->nullable();
        $table->enum('status', ['PENDING', 'COMPLETED', 'EXPIRED', 'CANCELLED'])->default('PENDING');
        $table->timestamps();

        // Unique constraint to prevent multiple active reopens for same student/date
        $table->unique(['student_id', 'reopened_date', 'status']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reopen_requests');
    }
};
