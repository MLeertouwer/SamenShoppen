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
        Schema::create('rides', function (Blueprint $table) {
            $table->id();
            // LET OP: DEZE TERUGZETTEN ALS INLOGSYSTEEM BESCHIKBAAR IS
            // $table->foreignId('driver_id')->constrained('memberships')->cascadeOnDelete();

            $table->integer('driver_id');

            // Mandatory columns
            $table->string('destination_store');
            $table->timestamp('departure_time');
            $table->integer('max_passengers');

            // Seperate columns for departure_place for both coördinates
            $table->decimal('departure_longitude', 10, 7);
            $table->decimal('departure_latitude', 10, 7);


            // Optional column (O)
            $table->string('status')->nullable();

            // ERD specifically asks for timestamps here
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rides');
    }
};
