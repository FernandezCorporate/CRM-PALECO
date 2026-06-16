<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Facades\Hash;

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

        // Filter by Search input
        $usersQuery->when($request->filled('search'), function ($query) use ($request) {
            $search = $request->search;
            return $query->where(function ($subQuery) use ($search) {
                $subQuery->where('username', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%");
            });
        });

        // 💡 CHANGE: Fetch results with pagination (4 per page)
        $users = $usersQuery->latest()->paginate(4)->withQueryString();

        // 3. Process each user inside the paginated list collection
        $users->getCollection()->transform(function ($user) {
            $user->legal_full_name = collect([
                $user->first_name ? Str::title($user->first_name) : null,
                $user->middle_name ? Str::upper(substr($user->middle_name, 0, 1)) . '.' : null,
                $user->last_name ? Str::title($user->last_name) : null,
                $user->name_ext ? Str::upper($user->name_ext) : null,
            ])->filter()->implode(' ');

            return $user;
        });

        // 4. Return view with both table items and total counts
        return view('admin.userManagement', compact('users', 'counts'));
    }

        public function storeUser(Request $request)
    {
        // 1. Enforce strict, SQL injection-proof validation checks
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'name_ext' => ['nullable', 'string', 'max:10'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'], // Preserves case & checks uniqueness
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'], // Formats and checks unique emails
            'password' => ['required', 'string', 'min:8', 'confirmed'], // Automatically checks password_confirmation match
            'role' => ['required', new Enum(UserRole::class)],
        ]);

        // 2. Map data and cleanly lowercase the required legal name string inputs
        $userData = [
            'first_name' => strtolower($validated['first_name']),
            'middle_name' => $validated['middle_name'] ? strtolower($validated['middle_name']) : null,
            'last_name' => strtolower($validated['last_name']),
            'name_ext' => $validated['name_ext'] ? strtolower($validated['name_ext']) : null,
            'username' => $validated['username'], // Casing preserved natively
            'email' => strtolower($validated['email']),
            'password' => Hash::make($validated['password']), // Securely hashes password strings
            'role' => $validated['role'],
            
            // 💡 Note on is_active: Because you set up ->default(true) inside your migration database layer,
            // you don't even need to declare or pass it here. MySQL will automatically write a 1 (true) for you!
        ];

        // 3. Commit row insertion securely via mass assignment rules
        User::create($userData);

        // 4. Redirect the admin back with a clean success toast alert session payload
        return redirect()->route('admin.userManagement')->with('success', 'User account created successfully.');
    }
}
