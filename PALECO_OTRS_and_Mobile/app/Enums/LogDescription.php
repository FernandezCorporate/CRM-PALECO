<?php

namespace App\Enums;

enum LogDescription: string
{
    // Static values allowed for 'description' in 'activity_logs' table
    case IP_LOCKED = 'account locked: too many attempts from IP';
    case USER_LOCKED = 'account locked: too many attempts for user, wait 15 minutes';
    case LOGIN_FAILED = 'failed login attempt';
    case LOGGED_IN = 'logged in';
    case LOGGED_OUT = 'logged out';

    // Dynamic Event Helpers
    
    // 1. Define the action made for the account status
    public static function userToggled(bool $isActive): string
    {
        $status = $isActive ? 'activated' : 'deactivated';
        return "{$status} user account";
    }

    // 2. Define the event made to the model data (create, update, delete)
    // 💡 Added $modelName parameter with a default fallback
    public static function modelUpdated(string $eventName, string $modelName = 'User account'): string
    {
        return "{$modelName} has been {$eventName}";
    }
}