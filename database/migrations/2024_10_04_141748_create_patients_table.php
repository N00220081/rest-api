<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('patients', function (Blueprint $table) {
        $table->id();
        $table->string('name', 128);
        $table->string('insurance', 255)->nullable();
        $table->string('email', 128)->unique();
        $table->string('phone', 16)->unique();
        $table->foreignId('doctor_id')->constrained()->onDelete('cascade'); // Assuming each patient is associated with a specific doctor
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
