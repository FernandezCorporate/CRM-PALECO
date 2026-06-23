<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Team;
use App\Models\Department;

class TeamController extends Controller
{
    public function teamManagement(Request $request)
    {
        // 1. Fetch all departments to populate the dropdown filter
        $departments = Department::orderBy('dept_name')->get();

        // 2. Base query: Eager load relationships for efficiency
        $teamQuery = Team::with(['users', 'department'])->orderBy('team_name');

        // 3. Apply Text Search (by team name)
        $teamQuery->when($request->filled('search'), function ($query) use ($request) {
            $search = $request->search;
            return $query->where('team_name', 'like', "%{$search}%");
        });

        // 4. Apply Department Dropdown Filter
        $teamQuery->when($request->filled('department') && $request->department !== 'all', function ($query) use ($request) {
            return $query->where('department_id', $request->department);
        });

        // 5. Paginate results
        $teams = $teamQuery->paginate(10)->withQueryString();

        return view('admin.teamManagement', compact('teams', 'departments'));
    }
}