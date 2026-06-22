<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Models
use App\Models\Department;
use App\Enums\UserRole;

class TeamController extends Controller
{
    public function teamManagement(Department $dept)
    {
        $teams = $dept->teams()->orderBy('team_name')->get();

        // 💡 The Enum ->value is safely inside the parenthesis
        $foremen = $dept->users()->where('role', UserRole::FOREMAN->value)->get();

        return response()->json([
            'status' => 'success',
            'department_name' => $dept->dept_name,
            'teams' => $teams,
            'foremen' => $foremen
        ]);
    }
}