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
        Schema::table('users', function (Blueprint $table) {
            // Drops the columns from the database when you run php artisan migrate
            $table->dropColumn(['shift_start', 'shift_end']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Re-adds the columns just in case you ever run php artisan migrate:rollback
            $table->time('shift_start')->nullable();
            $table->time('shift_end')->nullable();
        });
    }
};