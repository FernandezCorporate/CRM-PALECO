<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['shift_start', 'shift_end']);
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn(['shift_start', 'shift_end']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->time('shift_start')->nullable();
            $table->time('shift_end')->nullable();
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->time('shift_start')->nullable();
            $table->time('shift_end')->nullable();
        });
    }
};