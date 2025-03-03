<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weekly_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->integer('week_number');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('learning_outcomes');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('supervisor_feedback')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            
            // Ensure a student can't submit multiple reports for the same week
            $table->unique(['student_id', 'week_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weekly_reports');
    }
};