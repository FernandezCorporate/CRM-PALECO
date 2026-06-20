<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Enums\UserRole;

class UserSeeder extends Seeder
{
    // Seeds the foundational accounts required for system access.
    public function run(): void
    {
        User::create([
            'first_name'  => 'Allen Glenn',
            'last_name'   => 'Administrator',
            'username'    => 'allenglenn',
            'email'       => 'allenglenn@paleco.com',
            'password'    => 'october',             // Securely hashed by the model cast.
            'role'        => UserRole::ADMIN,       // Uses your strict Enum for role assignment.
            'is_active'   => true,
        ]);
    }
}