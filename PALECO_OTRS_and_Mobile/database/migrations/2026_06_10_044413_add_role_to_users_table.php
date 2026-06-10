<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Enums\UserRole; // Import the UserRole enum

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Collects ['admin', 'manager', 'employee'] dynamically from your enum file
            $table->enum('role', array_column(UserRole::cases(), 'value'))
            ->default(UserRole::FIELD_PERSONNEL->value)
            ->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drops the 'role' column when rolling back the migration
            $table->dropColumn('role');
        });
    }
};
