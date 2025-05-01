<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('course_name')->unique(); // Course name
            $table->string('course_code')->unique(); // Unique course code
            $table->integer('required_hours');
            $table->boolean('allows_custom_hours')->default(false); //if athelete
            $table->integer('custom_hours')->nullable(); //if athelete
            $table->unsignedBigInteger('academic_year_id')->nullable();
        
            $table->timestamps();
            $table->foreign('academic_year_id')->references('id')->on('academics')->onDelete('cascade');
            // Define the foreign key constraint
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
}
