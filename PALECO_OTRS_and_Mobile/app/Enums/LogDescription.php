<?php

namespace App\Enums;

enum LogDescription: string
{
    // Static Events
    case IP_LOCKED = 'account locked: too many attempts from IP';
    case USER_LOCKED = 'account locked: too many attempts for user, wait 15 minutes';
    case LOGIN_FAILED = 'failed login attempt';
    case LOGGED_IN = 'logged in';
    case LOGGED_OUT = 'logged out';

    // Dynamic Event Helpers
    public static function userToggled(bool $isActive): string
    {
        $status = $isActive ? 'activated' : 'deactivated';
        return "{$status} user account";
    }

    public static function modelUpdated(string $eventName): string
    {
        return "User account has been {$eventName}";
    }
}