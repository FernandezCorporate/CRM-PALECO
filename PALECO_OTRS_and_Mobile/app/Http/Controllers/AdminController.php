<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function userManagement(Request $request)
    {
        // 1. Calculate active account counts for the top metric cards
        $counts = [
            'admin' => User::where('role', UserRole::ADMIN)->where('is_active', true)->count(),
            'cwd' => User::where('role', UserRole::CWD)->where('is_active', true)->count(),
            'foreman' => User::where('role', UserRole::FOREMAN)->where('is_active', true)->count(),
            'field_personnel' => User::where('role', UserRole::FIELD_PERSONNEL)->where('is_active', true)->count(),
        ];

        // 2. Build the query for the user data table
        $usersQuery = User::query();

        // Filter by Role Tab if a specific role is selected (and it's not 'all')
        $usersQuery->when($request->filled('role') && $request->role !== 'all', function ($query) use ($request) {
            return $query->where('role', $request->role);
        });

        // Filter by Search input (Search by name, username, or email)
        $usersQuery->when($request->filled('search'), function ($query) use ($request) {
            $search = $request->search;
            return $query->where(function ($subQuery) use ($search) {
                $subQuery->where('username', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         // If you have a separate name field or combination:
                         ->orWhere('username', 'like', "%{$search}%"); 
            });
        });

        // Fetch filtered results (you can also change ->get() to ->paginate(10))
        $users = $usersQuery->latest()->get();

        // 3. Return view with both table items and total counts
        return view('admin.userManagement', compact('users', 'counts'));
    }
}
