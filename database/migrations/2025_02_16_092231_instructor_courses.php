<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instructor_courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instructor_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('academic_year_id');
            $table->boolean('is_verified')->default(false);
            $table->timestamps();

            $table->foreign('instructor_id')
                ->references('id')
                ->on('instructors')
                ->onDelete('cascade');

            $table->foreign('course_id')
                ->references('id')
                ->on('courses')
                ->onDelete('cascade');
            $table->foreign('academic_year_id')
                ->references('id')
                ->on('academics')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instructor_courses');
    }
};
