<?php

// Defines the address of LogDescription.php
namespace App\Enums;

// Defines the valid description inputs
enum LogDescription: string
{
    // Static values allowed for 'description' in 'activity_logs' table
    case IP_LOCKED = 'account locked: too many attempts from IP';
    case USER_LOCKED = 'account locked: too many attempts for user, wait 15 minutes';
    case LOGIN_FAILED = 'failed login attempt';
    case LOGGED_IN = 'logged in';
    case LOGGED_OUT = 'logged out';

    // Dynamic Event Helpers
    
    // Define the description for toggling of account status
    public static function userToggled(bool $isActive): string
    {
        $status = $isActive ? 'activated' : 'deactivated';  // One-line if-else statement to define the value of the variable.
        return "{$status} user account";                    // Description string output
    }

    // Define the description for the event on the 'Users' table (create, update, delete)
    public static function modelUpdated(string $eventName, string $modelName = 'User account'): string
    {
        return "{$modelName} has been {$eventName}";       // Description string output
    }

    public static function memberAssigned(int $memberCount, string $teamName): string
    {
        return "{$memberCount} personnel have been assigned to {$teamName}";
    }
}