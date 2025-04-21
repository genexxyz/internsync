<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('endorsement_letter_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('requested_by')->constrained('students')->onDelete('cascade');
            $table->enum('status', ['requested', 'for_pickup', 'picked_up'])->default('requested');
            $table->timestamp('requested_at')->nullable();
            $table->timestamp('for_pickup_at')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->string('received_by')->nullable();
            $table->text('admin_remarks')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            // Add indexes for frequently queried columns
            $table->index('status');
            $table->index('requested_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('endorsement_letter_requests');
    }
};