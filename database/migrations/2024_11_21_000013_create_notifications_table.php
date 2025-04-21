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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type');  // task_approved, moa_ready, etc.
            $table->string('title');
            $table->text('message')->nullable();
            $table->string('link')->nullable();
            $table->string('icon')->default('fa-bell');  // default font awesome icon
            $table->boolean('is_read')->default(false);
            $table->boolean('is_archived')->default(false);
            $table->timestamps();

            // Add index for common queries
            $table->index(['user_id', 'is_read', 'is_archived']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};