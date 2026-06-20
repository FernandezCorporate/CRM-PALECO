<?php

namespace App\Http\Controllers;

// Import models
use App\Models\User;
use App\Models\Department;

// Import Enum rules
use App\Enums\UserRole;
use App\Enums\LogName;
use App\Enums\LogDescription;

// Import Requests (Cleans and validates user inputs)
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

use Illuminate\Http\Request;    // Handles incoming HTTP requests.
use Illuminate\Support\Str;     // Provides string manipulation modules.
use Illuminate\Support\Facades\Hash;    // Provides password hashing (uses the Bcryt hashing alogrithm)
use Illuminate\Support\Facades\Auth;    // Provides authentication functions

class AdminController extends Controller
{
    // Builds context data that shall be renderred on the 'User Management' page
    public function userManagement(Request $request)
    {

        $departments = Department::orderBy('dept_name')->get();

        // Queries and stores the total count for each user role
        $counts = [
            'admin' => User::where('role', UserRole::ADMIN)->where('is_active', true)->count(),
            'cwd' => User::where('role', UserRole::CWD)->where('is_active', true)->count(),
            'foreman' => User::where('role', UserRole::FOREMAN)->where('is_active', true)->count(),
            'field_personnel' => User::where('role', UserRole::FIELD_PERSONNEL)->where('is_active', true)->count(),
        ];

        // Intialize base query
        $usersQuery = User::with('department');

        // Filters the users based on the selected user role buttons on the interface
        // Only executes when the selected card is not 'all'
        $usersQuery->when($request->filled('role') && $request->role !== 'all', function ($query) use ($request) {
            return $query->where('role', $request->role);
        });

        // Filters the users based on the search box keywords
        // Keywords are matched with 'username', 'email', 'first_name', or 'last_name'
        // Search filters only operate within the context of the currently selected role
        $usersQuery->when($request->filled('search'), function ($query) use ($request) {
            $search = $request->search;
            return $query->where(function ($subQuery) use ($search) {
                $subQuery->where('username', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%");
            });
        });

        // Paginates search results and limits each query for up to 10 users only
        $users = $usersQuery->latest()->paginate(10)->withQueryString();

        // Formats name fields into one variable stored as 'legal_full_name'
        $users->getCollection()->transform(function ($user) {
            $user->legal_full_name = collect([
                $user->first_name ? Str::title($user->first_name) : null,
                $user->middle_name ? Str::upper(substr($user->middle_name, 0, 1)) . '.' : null,
                $user->last_name ? Str::title($user->last_name) : null,
                $user->name_ext ? Str::upper($user->name_ext) : null,
            ])->filter()->implode(' ');

            return $user;
        });

        // Specifies which blade template shall the user be redirected for this public function
        // Also passes the context data of queried users and the count for each role
        return view('admin.userManagement', compact('users', 'counts', 'departments'));
    }

    // Handles user account creation
    public function addUser(StoreUserRequest $request)
    {
        // Looks into the 'rules()' function of 'StoreUserRequest.php'
        // It extracts only the specific pieces of data that successfully passed the defined rules
        $validated = $request->validated();

        // Prepares the values to be stored into the 'users' table
        $userData = [
            'first_name'  => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name'   => $validated['last_name'],
            'name_ext'    => $validated['name_ext'] ?? null,
            'username'    => $validated['username'], 
            'email'       => $validated['email'],
            'password'    => Hash::make($validated['password']), 
            'role'        => $validated['role'],
            'department_id' => $validated['department_id'] ?? null, 
            'shift_start'   => $validated['shift_start'] ?? null,   
            'shift_end'     => $validated['shift_end'] ?? null,     
        ];

        // Executes an INSERT query into 'users' using the prepared values
        User::create($userData);

        // Defines the redirect page of a user upon a successful insert
        // Passes the context data for the success text that will be renderred on the interface
        return redirect()->route('admin.userManagement')->with('success', 'User account created successfully.');
    }

    // Handles updating user information
    public function updateUser(UpdateUserRequest $request, User $user)
    {
        // Looks into the 'rules()' function of 'UpdateUserRequest.php'
        // It extracts only the specific pieces of data that successfully passed the defined rules
        $validated = $request->validated();

        // Updates each attribute of the selected user with the new values
        $user->first_name  = $validated['first_name'];
        $user->middle_name = $validated['middle_name'] ?? null;
        $user->last_name   = $validated['last_name'];
        $user->name_ext    = $validated['name_ext'] ?? null;
        $user->username    = $validated['username'];
        $user->email       = $validated['email'];
        
        // 💡 Security Check: Only assign the role if the admin is editing SOMEONE ELSE
        if (Auth::id() !== $user->id) {
            $user->role = $validated['role'];
        }
        
        $user->department_id = $validated['department_id'] ?? null; 
        $user->shift_start   = $validated['shift_start'] ?? null;   
        $user->shift_end     = $validated['shift_end'] ?? null;     

        // Ensures password will only be updated if the input was not empty
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Terminates the update if no changes were detected 
        if (! $user->isDirty()) {
            return redirect()
                ->route('admin.userManagement')
                ->with('success', 'No changes were made to the user profile.');
        }

        // Commits the update query as long as there is at least one change detected
        $user->save();

        // Defines the redirect page of a user upon successful update
        // Passes the context data for the success text that will be renderred on the interface
        return redirect()
            ->route('admin.userManagement')
            ->with('success', 'User updated successfully.');
    }

    // Handles the activation and deactivation of user accounts
    public function toggleStatus(User $user)
    {

        // IMPORTANT! Prevents current admin users from deactivating their own accounts. 
        if (Auth::id() === $user->id) {
            return back()->withErrors(['user' => 'You cannot deactivate your currently active session account.']);
        }

        // Toggles the current state of the account status by changing the 'is_active' boolean value
        $user->is_active = !$user->is_active;
        $user->save();

        // Manually logs the toggle account status into the 'activity_logs' table.
        activity(LogName::ADMIN_ACTION->value)
            ->performedOn($user)
            ->causedBy(Auth::user())
            ->log(LogDescription::userToggled($user->is_active));

        // Dynamically stores the toggle event that was performed
        $statusWord = $user->is_active ? 'activated' : 'deactivated';

        // Defines the redirect page of a user upon successful changing of account status
        // Passes the context data for the success text that will be renderred on the interface
        return redirect()
            ->route('admin.userManagement')
            ->with('success', "User account has been {$statusWord}.");
    }

    public function deptManagement()
    {
        // 💡 Use the new relationship defined in the Department model
        $departments = Department::with('foremen')->orderBy('dept_name')->get();

        // Transform the foremen collection into a formatted string
        $departments->each(function ($department) {
            $department->foremen_list = $department->foremen->map(function ($user) {
                return Str::title($user->first_name . ' ' . $user->last_name);
            })->implode(', '); 
        });

        return view('admin.deptManagement', compact('departments'));
    }
}