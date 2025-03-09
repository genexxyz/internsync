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
        Schema::create('supervisors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('company_department_id')->nullable();
            $table->string('position');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();
            // $table->date('birthday')->nullable();
            $table->string('contact')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->string('supporting_doc')->nullable();
            $table->string('image')->nullable();
            $table->string('e_signature')->nullable();
            $table->boolean('is_profile_complete')->default(false);
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supervisors');
    }
};
