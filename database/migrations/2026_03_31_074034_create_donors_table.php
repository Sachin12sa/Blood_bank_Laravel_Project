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
    Schema::create('donors', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->enum('blood_group', ['A+','A-','B+','B-','O+','O-','AB+','AB-']);
        $table->string('phone')->nullable();
        $table->string('address')->nullable();
        $table->date('date_of_birth')->nullable();
        $table->boolean('is_eligible')->default(true);
        $table->timestamp('last_donated_at')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donors');
    }
};
