<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deployment_id')->constrained()->onDelete('cascade');
            $table->foreignId('supervisor_id')->constrained('supervisors')->onDelete('cascade');
            
            // Ratings
            $table->integer('quality_work')->comment('Max: 20');
            $table->integer('completion_time')->comment('Max: 15');
            $table->integer('dependability')->comment('Max: 15');
            $table->integer('judgment')->comment('Max: 10');
            $table->integer('cooperation')->comment('Max: 10');
            $table->integer('attendance')->comment('Max: 10');
            $table->integer('personality')->comment('Max: 10');
            $table->integer('safety')->comment('Max: 10');
            
            // Total score will be calculated from above ratings
            $table->integer('total_score')->comment('Sum of all ratings');
            
            // Recommendation
            $table->text('recommendation');
            
            // Timestamps
            $table->timestamps();

            // Ensure one evaluation per deployment
            $table->unique('deployment_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};