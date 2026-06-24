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
        $departments = Department::orderBy('dept_name')->get();

        // 💡 Eager load the new 'shifts' relationship
        $teamQuery = Team::with(['users', 'department', 'shifts'])->orderBy('team_name');

        $teamQuery->when($request->filled('search'), function ($query) use ($request) {
            return $query->where('team_name', 'like', "%{$request->search}%");
        });

        $teamQuery->when($request->filled('department') && $request->department !== 'all', function ($query) use ($request) {
            return $query->where('department_id', $request->department);
        });

        $teams = $teamQuery->paginate(10)->withQueryString();

        return view('admin.pages.teamManagement', compact('teams', 'departments'));
    }
}