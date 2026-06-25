<?php

// Defines the address of DayofWeek.php
namespace App\Enums;    

// Defines the valid day inputs
enum DayOfWeek: string  
{
    // Static values allowed for 'day' in 'user_shifts' and 'team_shifts' table
    case MONDAY = 'Monday';
    case TUESDAY = 'Tuesday';
    case WEDNESDAY = 'Wednesday';
    case THURSDAY = 'Thursday';
    case FRIDAY = 'Friday';
    case SATURDAY = 'Saturday';
    case SUNDAY = 'Sunday';

    // Maps a 0-indexed numerical value for each day of a week
    // Used for transforming shift_start and shift_end into absolute weekly minutes, which is used to identify overlapping schedules for a user
    
    public function numericIndex(): int
    {
        return match($this){
            self::MONDAY => 0,
            self::TUESDAY => 1,
            self::WEDNESDAY => 2,
            self::THURSDAY => 3,
            self::FRIDAY => 4,
            self::SATURDAY => 5,
            self::SUNDAY => 6,
        };
    }
}