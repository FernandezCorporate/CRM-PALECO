<?php
namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case CWD = 'cwd';
    case FOREMAN = 'foreman';
    case FIELD_PERSONNEL = 'field_personnel';
}
