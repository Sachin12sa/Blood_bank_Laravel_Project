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
    Schema::create('blood_units', function (Blueprint $table) {
        $table->id();
        $table->enum('blood_group', ['A+','A-','B+','B-','O+','O-','AB+','AB-']);
        $table->foreignId('donor_id')->nullable()->constrained()->nullOnDelete();
        $table->date('collected_at');
        $table->date('expires_at');   // 42 days from collection
        $table->enum('status', ['available', 'reserved', 'used', 'expired'])->default('available');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_units');
    }
};
