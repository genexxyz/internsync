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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('system_name');
            $table->string('default_theme');
            $table->string('default_logo')->nullable();
            $table->string('header_image')->nullable();
            $table->string('footer_image')->nullable();
            $table->string('school_name');
            $table->string('school_address');
            $table->string('system_email');
            $table->string('system_contact');
            $table->integer('minimum_hours')->default(0);
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();
        
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
