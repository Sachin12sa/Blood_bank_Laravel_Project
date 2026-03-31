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
    Schema::create('blood_requests', function (Blueprint $table) {
        $table->id();
        $table->foreignId('hospital_id')->constrained()->cascadeOnDelete();
        $table->enum('urgency', ['normal', 'critical'])->default('normal');
        $table->enum('status', ['pending', 'approved', 'dispatched', 'rejected'])->default('pending');
        $table->text('notes')->nullable();
        $table->timestamp('approved_at')->nullable();
        $table->timestamp('dispatched_at')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_requests');
    }
};
