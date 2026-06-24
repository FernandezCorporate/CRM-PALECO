<?php

// Defines the address of UserRole.php
namespace App\Enums;

// Defines the valid user/account role inputs
enum UserRole: string
{
    // Static values allowed for 'role' in 'users' table
    case ADMIN = 'admin';
    case CWD = 'cwd';
    case FOREMAN = 'foreman';
    case FIELD_PERSONNEL = 'field_personnel';

    // Translates the backend value (lowercased) to a human-readable label (proper casing)
    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Admin',
            self::CWD => 'CWD Officer',
            self::FOREMAN => 'Foreman',
            self::FIELD_PERSONNEL => 'Field Personnel',
        };
    }

    // Dictates the Tailwind CSS colors for the UI badges
    public function badgeClasses(): string
    {
        return match($this) {
            self::ADMIN => 'bg-purple-100 text-purple-700',
            self::CWD => 'bg-blue-100 text-blue-700',
            self::FOREMAN => 'bg-orange-100 text-orange-700',
            self::FIELD_PERSONNEL => 'bg-emerald-100 text-emerald-700',
        };
    }
}