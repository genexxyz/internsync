<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->foreignId('journal_id')->constrained();
            $table->string('status');
            $table->timestamp('changed_at');
            $table->timestamps();

            // Add indexes for performance
            $table->index(['task_id', 'changed_at']);
            $table->index(['journal_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_histories');
    }
};