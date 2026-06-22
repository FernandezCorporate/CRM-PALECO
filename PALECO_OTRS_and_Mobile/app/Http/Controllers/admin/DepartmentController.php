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

class DepartmentController extends Controller
{
    public function deptManagement()
    {
        $departments = Department::with(['users' => function ($query) {
            $query->where('role', UserRole::FOREMAN);
        }])->orderBy('dept_name')->get();

        $departments->each(function ($department) {
            $department->foremen_list = $department->users->map(function ($user) {
                return Str::title($user->first_name . ' ' . $user->last_name);
            })->implode(', '); 
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