<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Creates the departments table to categorize organization structure.
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();                                 // Primary Key.
            $table->string('dept_name')->unique();        // Unique department identifier.
            $table->text('dept_description')->nullable(); // Optional context for the department.
            $table->timestamps();                         // Tracks creation and update events for auditing.
        });
    }

    // Drops the departments table if the migration is rolled back.
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};