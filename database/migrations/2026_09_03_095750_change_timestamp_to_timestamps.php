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
        Schema::table('memberships', function (Blueprint $table) {
            // 1. Verwijder de oude kolom 'timestamp'
            $table->dropColumn('timestamp');

            // 2. Voeg de standaard 'created_at' en 'updated_at' toe
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('memberships', function (Blueprint $table) {
            // 1. Verwijder de standaard 'created_at' en 'updated_at'
            $table->dropTimestamps();

            // 2. Voeg de oude kolom 'timestamp' weer toe
            $table->timestamp('timestamp')->nullable();
        });
    }
};
