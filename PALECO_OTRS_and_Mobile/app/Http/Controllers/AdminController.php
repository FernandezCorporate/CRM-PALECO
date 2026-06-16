<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

        // Filter by Search input (Updated to search new legal name fields too)
        $usersQuery->when($request->filled('search'), function ($query) use ($request) {
            $search = $request->search;
            return $query->where(function ($subQuery) use ($search) {
                $subQuery->where('username', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%");
            });
        });

        // Fetch filtered results
        $users = $usersQuery->latest()->get();

        $users->transform(function ($user) {
            $user->legal_full_name = collect([
                // Str::title() transforms "allen glenn" into "Allen Glenn"
                $user->first_name ? Str::title($user->first_name) : null,
                
                // Converts middle name to a clean single capital letter initial (e.g., "F.")
                $user->middle_name ? Str::upper(substr($user->middle_name, 0, 1)) . '.' : null,
                
                // Transforms last name words accurately
                $user->last_name ? Str::title($user->last_name) : null,
                
                // Ensures extensions stay capitalized (e.g., "JR.")
                $user->name_ext ? Str::upper($user->name_ext) : null,
            ])->filter()->implode(' ');

            return $user;
        });

        // 4. Return view with both table items and total counts
        return view('admin.userManagement', compact('users', 'counts'));
    }
}
