<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Upgrades the table to enforce strict department assignments.
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 1. Drop the existing foreign key constraint
            $table->dropForeign(['department_id']);

            // 2. Modify the column to reject NULL values
            $table->unsignedBigInteger('department_id')->nullable(false)->change();

            // 3. Re-establish the foreign key with the new restrictOnDelete rule
            $table->foreign('department_id')
                  ->references('id')
                  ->on('departments')
                  ->restrictOnDelete();
        });
    }

    // Safely rolls back to the original optional state if needed.
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 1. Drop the strict constraint
            $table->dropForeign(['department_id']);

            // 2. Allow NULL values again
            $table->unsignedBigInteger('department_id')->nullable()->change();

            // 3. Re-establish the original nullOnDelete rule
            $table->foreign('department_id')
                  ->references('id')
                  ->on('departments')
                  ->nullOnDelete();
        });
    }
};