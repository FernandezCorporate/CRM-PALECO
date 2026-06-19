<?php

namespace App\Enums;

enum LogName: string
{
    case ADMIN_ACTION = 'admin_action';
    case SECURITY_ALERT = 'security_alert';
    case FAILED_LOGIN = 'failed_login';
    case SYSTEM_DEFAULT = 'default';
    case USER_MANAGEMENT = 'user_management';
}