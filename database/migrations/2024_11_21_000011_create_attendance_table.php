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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->date('date');
            $table->time('time_in')->nullable();
            $table->time('time_out')->nullable();
            $table->time('start_break')->nullable();
            $table->time('end_break')->nullable();
            $table->time('total_hours')->nullable();
            $table->enum('status', ['regular', 'late', 'absent']);
            $table->tinyInteger('is_approved')->default(0)->comment('0: pending, 1: approved, 2: rejected');
            $table->timestamps();
        
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
