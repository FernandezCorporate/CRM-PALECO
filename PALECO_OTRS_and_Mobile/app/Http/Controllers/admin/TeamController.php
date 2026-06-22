<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Models
use App\Models\Department;

class TeamController extends Controller
{
    public function teamManagement(Department $dept)
    {
        $teams = $dept->teams()->orderBy('team_name')->get();

        return response()->json([
            'status' => 'success',
            'department_name' => $dept->dept_name,
            'teams' => $teams
        ]);
    }
}