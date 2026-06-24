<?php

// Defines the address of LogName.php
namespace App\Enums;

// Defines the valid log name inputs
enum LogName: string
{
    // Static values allowed for 'log_name' in 'activity_logs' table
    case ADMIN_ACTION = 'admin_action';
    case SECURITY_ALERT = 'security_alert';
    case FAILED_LOGIN = 'failed_login';
    case SYSTEM_DEFAULT = 'default';
    case USER_MANAGEMENT = 'user_management';
    case DEPARTMENT_MANAGEMENT = 'department_management';
    case TEAM_MANAGEMENT = 'team_management';
}