<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Department;
use App\Enums\UserRole;
use App\Enums\LogName;
use App\Enums\LogDescription;
use App\Enums\UserSort;

use App\Http\Requests\user\StoreUserRequest;
use App\Http\Requests\user\UpdateUserRequest;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function userManagement(Request $request)
    {
        $counts = [
            'admin' => User::where('role', UserRole::ADMIN)->where('is_active', true)->count(),
            'cwd' => User::where('role', UserRole::CWD)->where('is_active', true)->count(),
            'foreman' => User::where('role', UserRole::FOREMAN)->where('is_active', true)->count(),
            'field_personnel' => User::where('role', UserRole::FIELD_PERSONNEL)->where('is_active', true)->count(),
        ];

        $usersQuery = User::with('department');
        $sort = UserSort::tryFrom($request->input('sort')) ?? UserSort::NEWEST;
        $sort->applyOrder($usersQuery);

        $usersQuery->when($request->filled('role') && $request->role !== 'all', function ($query) use ($request) {
            return $query->where('role', $request->role);
        });

        $usersQuery->when($request->filled('search'), function ($query) use ($request) {
            $search = $request->search;
            return $query->where(function ($subQuery) use ($search) {
                $subQuery->where('username', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%");
            });
        });

        $users = $usersQuery->latest()->paginate(10)->withQueryString();

        $users->getCollection()->transform(function ($user) {
            $user->legal_full_name = collect([
                $user->first_name ? Str::title($user->first_name) : null,
                $user->middle_name ? Str::upper(substr($user->middle_name, 0, 1)) . '.' : null,
                $user->last_name ? Str::title($user->last_name) : null,
                $user->name_ext ? Str::upper($user->name_ext) : null,
            ])->filter()->implode(' ');

            return $user;
        });

        return view('admin.pages.userManagement', compact('users', 'counts'));
    }

    public function addUserForm(Request $request)
    {
        $departments = Department::orderBy('dept_name')->get();
        $userRoles = UserRole::cases();
        return view('admin.forms.userForm', compact('departments', 'userRoles'));
    }

    public function addUser(StoreUserRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function() use ($validated) {
            User::create([
                'first_name'    => $validated['first_name'],
                'middle_name'   => $validated['middle_name'] ?? null,
                'last_name'     => $validated['last_name'],
                'name_ext'      => $validated['name_ext'] ?? null,
                'username'      => $validated['username'], 
                'email'         => $validated['email'] ?? null,
                'contact'       => $validated['contact'],
                'password'      => Hash::make($validated['password']), 
                'role'          => $validated['role'],
                'department_id' => $validated['department_id'] ?? null, 
            ]);
        });

        return redirect()->route('admin.userManagement')->with('success', 'User account created successfully.');
    }

    public function updateUserForm(User $user) 
    {
        $departments = Department::orderBy('dept_name')->get();
        $userRoles = UserRole::cases();
        
        return view('admin.forms.userForm', compact('departments', 'user', 'userRoles'));     
    }

    public function updateUser(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        // 1. Assign standard attributes
        $user->first_name   = $validated['first_name'];
        $user->middle_name  = $validated['middle_name'] ?? null;
        $user->last_name    = $validated['last_name'];
        $user->name_ext     = $validated['name_ext'] ?? null;
        $user->username     = $validated['username'];
        $user->email        = $validated['email'] ?? null;
        $user->contact      = $validated['contact'];
        
        if (Auth::id() !== $user->id) {
            $user->role = $validated['role'];
        }
        
        $user->department_id = $validated['department_id']; 

        // 2. The Escape Hatch: If nothing changed, block the database save entirely
        if (!$user->isDirty()) {
            return redirect()
                ->route('admin.userManagement')
                ->with('success', 'No changes were made to the user profile.');
        }

        // 3. Commit changes
        DB::transaction(function() use ($user) {
            // The Spatie LogsActivity trait handles logging these attribute changes automatically
            $user->save(); 
        });

        return redirect()->route('admin.userManagement')->with('success', 'User updated successfully.');
    }

    public function toggleStatus(User $user)
    {
        if (Auth::id() === $user->id) {
            return back()->withErrors(['user' => 'You cannot deactivate your currently active session account.']);
        }

        DB::transaction(function() use ($user) {
            $user->is_active = !$user->is_active;
            $user->save();

            activity(LogName::ADMIN_ACTION->value)
                ->performedOn($user)
                ->causedBy(Auth::user())
                ->log(LogDescription::userToggled($user->is_active));
        });

        $statusWord = $user->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->route('admin.userManagement')
            ->with('success', "User account has been {$statusWord}.");
    }

    public function getUserDetails(User $user)
    {
        return view('admin.pages.userDetails', compact('user'));
    }
}