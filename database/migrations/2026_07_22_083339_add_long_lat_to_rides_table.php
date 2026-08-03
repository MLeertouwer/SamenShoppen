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
        Schema::table('rides', function (Blueprint $table) {
            $table->string('destination_address')->nullable()->after('destination_store');
            $table->decimal('destination_longitude', 10, 7)->after('destination_store');
            $table->decimal('destination_latitude', 10, 7)->after('destination_store');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rides', function (Blueprint $table) {
            $table->dropColumn('destination_longitude');
            $table->dropColumn('destination_latitude');
        });
    }
};
