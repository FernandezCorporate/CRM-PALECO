<?php
namespace App\Enums;

// Static values allowed for 'role' in 'users' table
enum UserRole: string
{
    case ADMIN = 'admin';
    case CWD = 'cwd';
    case FOREMAN = 'foreman';
    case FIELD_PERSONNEL = 'field_personnel';
}
