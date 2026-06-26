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
}