<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Added with blank defaults so existing rows don't break the migration
            $table->string('first_name')->default('')->after('id');
            $table->string('middle_name')->nullable()->after('first_name');
            $table->string('last_name')->default('')->after('middle_name');
            $table->string('name_ext', 10)->nullable()->after('last_name');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'middle_name', 'last_name', 'name_ext']);
        });
    }
};
