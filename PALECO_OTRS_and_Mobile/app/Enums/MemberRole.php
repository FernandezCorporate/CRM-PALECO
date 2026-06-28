<?php

// Defines the address of LogName.php
namespace App\Enums;

// Defines the valid member role inputs
enum MemberRole : string
{
    // Static values allowed for 'role' in 'team_members' table
    case LEADER = 'leader';
    case MEMBER = 'member';
    case BACKUP = 'backup';
}
