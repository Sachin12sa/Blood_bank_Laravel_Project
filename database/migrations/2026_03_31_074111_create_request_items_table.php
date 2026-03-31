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
    Schema::create('request_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('blood_request_id')->constrained()->cascadeOnDelete();
        $table->enum('blood_group', ['A+','A-','B+','B-','O+','O-','AB+','AB-']);
        $table->integer('units_requested');
        $table->integer('units_fulfilled')->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_items');
    }
};
