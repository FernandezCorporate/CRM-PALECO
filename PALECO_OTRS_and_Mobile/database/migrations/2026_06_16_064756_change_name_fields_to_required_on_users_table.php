<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Removes the default value and alters the column to be strictly required
            $table->string('first_name')->default(null)->change();
            $table->string('last_name')->default(null)->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // If rolled back, adds the blank string default constraints back
            $table->string('first_name')->default('')->change();
            $table->string('last_name')->default('')->change();
        });
    }
};
