<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Modifies the users table to include department assignments and shift schedules.
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Adds the nullable foreign key for department leadership
            $table->foreignId('department_id')
                  ->nullable()
                  ->after('role')
                  ->constrained('departments')
                  ->nullOnDelete(); 

            // Adds nullable time fields for employee shift tracking
            $table->time('shift_start')->nullable()->after('department_id');
            $table->time('shift_end')->nullable()->after('shift_start');
        });
    }

    // Reverts the modification by dropping the foreign key and the new columns.
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // You must drop the foreign key constraint BEFORE dropping the column
            $table->dropForeign(['department_id']);
            
            // Drop all newly added columns
            $table->dropColumn(['department_id', 'shift_start', 'shift_end']);
        });
    }
};