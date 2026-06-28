<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Change team_members.role to string
        Schema::table('team_members', function (Blueprint $table) {
            $table->string('role')->change();
        });

        // Change users.role to string
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->change();
        });
    }

    public function down(): void
    {

    }
};