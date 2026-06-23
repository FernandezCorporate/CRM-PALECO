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
    public function deptManagement(Request $request)
    {
        // 1. Start the base query
        $deptQuery = Department::with(['teams', 'users'])->orderBy('dept_name');

        // 2. Apply search filter if the user typed something
        $deptQuery->when($request->filled('search'), function ($query) use ($request) {
            $search = $request->search;
            return $query->where(function ($subQuery) use ($search) {
                $subQuery->where('dept_name', 'like', "%{$search}%")
                         ->orWhere('dept_description', 'like', "%{$search}%");
            });
        });

        // 3. Paginate the filtered results
        $departments = $deptQuery->paginate(10)->withQueryString();

        // 4. Transform the collection (Your existing logic)
        $departments->getCollection()->transform(function ($department) {
            $department->total_teams = $department->teams->count();
            $department->total_personnel = $department->users->where('role', \App\Enums\UserRole::FIELD_PERSONNEL)->count();
            $department->total_foremen = $department->users->where('role', \App\Enums\UserRole::FOREMAN)->count();

            $department->unique_shifts = $department->teams
                ->filter(fn($u) => $u->shift_start && $u->shift_end)
                ->map(function($u) {
                    $start = \Carbon\Carbon::parse($u->shift_start)->format('gA');
                    $end = \Carbon\Carbon::parse($u->shift_end)->format('gA');

                    $start = str_replace(['12AM', '12PM'], ['12MN', '12NN'], $start);
                    $end = str_replace(['12AM', '12PM'], ['12MN', '12NN'], $end);

                    return $start . ' to ' . $end;
                })
                ->unique()
                ->values();
                
            return $department;
        });

        return view('admin.deptManagement', compact('departments'));
    }

    public function addDeptForm(Request $request)
    {
        return view('admin.forms.deptForm');
    }

    public function addDept(StoreDepartmentRequest $request)
    {
        Department::create($request->validated());

        return redirect()->route('admin.deptManagement')->with('success', 'Department created successfully.');
    }

    public function updateDeptForm(Department $dept)
    {
        return view('admin.forms.deptForm', compact('dept'));
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