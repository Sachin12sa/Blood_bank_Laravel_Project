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
    Schema::create('blood_inventories', function (Blueprint $table) {
        $table->id();
        $table->enum('blood_group', ['A+','A-','B+','B-','O+','O-','AB+','AB-'])->unique();
        $table->integer('total_units')->default(0);
        $table->integer('available_units')->default(0);
        $table->integer('threshold')->default(5); // alert if below this
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_inventories');
    }
};
