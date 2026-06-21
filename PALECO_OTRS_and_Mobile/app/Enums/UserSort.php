<?php

namespace App\Enums;

enum UserSort : string
{
    case NEWEST = 'newest';
    case OLDEST = 'oldest';
    case USERNAME_ASC = 'username_asc';
    case USERNAME_DESC = 'username_desc';
    case LASTNAME_ASC = 'lastname_asc';
    case LASTNAME_DESC  = 'lastname_desc';
    case FIRSTNAME_ASC = 'firstname_asc';
    case FIRSTNAME_DESC = 'firstname_desc';

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
