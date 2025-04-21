<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('moa_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('company_number');
            $table->string('officer_name');
            $table->string('officer_position');
            $table->string('witness_name');
            $table->string('witness_position');
            $table->foreignId('requested_by')->constrained('students')->onDelete('cascade');
            $table->enum('status', ['requested', 'for_pickup', 'picked_up', 'received_by_company'])
                  ->default('requested');
            $table->timestamp('requested_at')->nullable();
            $table->timestamp('for_pickup_at')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('received_by_company_at')->nullable();
            $table->string('received_by_student')->nullable();
            $table->foreignId('received_by_supervisor')->nullable()->constrained('supervisors')->onDelete('set null');
            $table->text('admin_remarks')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('set null');
            $table->timestamps();

            // Add indexes for commonly queried columns
            $table->index('status');
            $table->index('requested_at');
            $table->index('company_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('moa_requests');
    }
};