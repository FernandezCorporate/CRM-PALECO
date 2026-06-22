<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Models & Enums
use App\Models\Department;
use App\Enums\UserRole;

// Requests
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;

// Facades
use Illuminate\Support\Str;

use Carbon\Carbon;

class DepartmentController extends Controller
{
    public function deptManagement()
    {
        $departments = Department::with(['teams', 'users'])->orderBy('dept_name')->get();

        $departments->each(function ($department) {
            $department->total_teams = $department->teams->count();
            $department->total_personnel = $department->users->where('role', UserRole::FIELD_PERSONNEL)->count();
            $department->total_foremen = $department->users->where('role', UserRole::FOREMAN)->count();

            $department->unique_shifts = $department->users
                ->filter(fn($u) => $u->shift_start && $u->shift_end)
                ->map(function($u) {
                    $start = Carbon::parse($u->shift_start)->format('gA');
                    $end = Carbon::parse($u->shift_end)->format('gA');

                    $start = str_replace(['12AM', '12PM'], ['12MN', '12NN'], $start);
                    $end = str_replace(['12AM', '12PM'], ['12MN', '12NN'], $end);

                    return $start . ' to ' . $end;
                })
                ->unique()
                ->values();
        });

        return view('admin.deptManagement', compact('departments'));
    }

    public function addDept(StoreDepartmentRequest $request)
    {
        Department::create($request->validated());

        return redirect()->route('admin.deptManagement')->with('success', 'Department created successfully.');
    }

    public function updateDept(UpdateDepartmentRequest $request, Department $dept)
    {
        $validated = $request->validated();

        $dept->dept_name = $validated['dept_name'];
        $dept->dept_description = $validated['dept_description'];

        if (! $dept->isDirty()){
            return redirect()
                ->route('admin.deptManagement')
                ->with('success', 'No changes were made to the selected department.');
        }

        $dept->save();

        return redirect()
            ->route('admin.deptManagement')
            ->with('success', 'Department updated successfully.');
    }
}