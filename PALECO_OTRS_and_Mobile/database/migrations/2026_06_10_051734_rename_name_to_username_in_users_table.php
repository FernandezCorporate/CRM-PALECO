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
            // 1. Rename the column from 'name' to 'username'
            $table->renameColumn('name', 'username');
        });

        Schema::table('users', function (Blueprint $table) {
            // 2. Add the unique constraint to the newly renamed username column
            $table->string('username')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 1. Remove the unique index constraint first
            $table->dropUnique(['username']);
        });

        Schema::table('users', function (Blueprint $table) {
            // 2. Rename it back to 'name' if you roll back
            $table->renameColumn('username', 'name');
        });
    }
};
