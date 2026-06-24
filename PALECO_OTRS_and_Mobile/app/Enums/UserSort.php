<?php

// Defines the address of UserSort.php
namespace App\Enums;

// Defines the valid sorting options
enum UserSort : string
{
    // Static values allowed for the <option> tag on the 'Order by' dropdown input 
    case NEWEST = 'newest';
    case OLDEST = 'oldest';
    case USERNAME_ASC = 'username_asc';
    case USERNAME_DESC = 'username_desc';
    case LASTNAME_ASC = 'lastname_asc';
    case LASTNAME_DESC  = 'lastname_desc';
    case FIRSTNAME_ASC = 'firstname_asc';
    case FIRSTNAME_DESC = 'firstname_desc';

    // Defines the orderBy() methods for each UserSort case
    // This sorting query is appended on the base query for the 'userManagement()' function in 'User.controller'
    public function applyOrder($query)
    {
        return match($this) {
            self::USERNAME_ASC  => $query->orderBy('username', 'asc'),
            self::USERNAME_DESC => $query->orderBy('username', 'desc'),           
            self::LASTNAME_ASC  => $query->orderBy('last_name', 'asc')->orderBy('first_name', 'asc'),
            self::LASTNAME_DESC => $query->orderBy('last_name', 'desc')->orderBy('first_name', 'desc'),
            self::FIRSTNAME_ASC  => $query->orderBy('first_name', 'asc')->orderBy('last_name', 'asc'),
            self::FIRSTNAME_DESC => $query->orderBy('first_name', 'desc')->orderBy('last_name', 'desc'),
            self::OLDEST    => $query->oldest(),
            self::NEWEST    => $query->latest(),
        };
    }

    // Translates the backend value (lowercased) to a human-readable label (proper casing)
    public function label(): string
    {
        return match($this) {
            self::USERNAME_ASC  => 'Username (A-Z)',
            self::USERNAME_DESC => 'Username (Z-A)',
            self::LASTNAME_ASC  => 'Last Name (A-Z)',
            self::LASTNAME_DESC => 'Last Name (Z-A)',
            self::FIRSTNAME_ASC  => 'First Name (A-Z)',
            self::FIRSTNAME_DESC => 'First Name (Z-A)',
            self::NEWEST    => 'Newest First',
            self::OLDEST    => 'Oldest First',
        };
    }
}
