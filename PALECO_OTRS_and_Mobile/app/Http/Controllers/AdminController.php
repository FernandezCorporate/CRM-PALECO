<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Enums\UserRole;
use App\Enums\LogName;
use App\Enums\LogDescription;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function userManagement(Request $request)
    {
        $counts = [
            'admin' => User::where('role', UserRole::ADMIN)->where('is_active', true)->count(),
            'cwd' => User::where('role', UserRole::CWD)->where('is_active', true)->count(),
            'foreman' => User::where('role', UserRole::FOREMAN)->where('is_active', true)->count(),
            'field_personnel' => User::where('role', UserRole::FIELD_PERSONNEL)->where('is_active', true)->count(),
        ];

        $usersQuery = User::query();

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

        return view('admin.userManagement', compact('users', 'counts'));
    }

    public function addUser(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $userData = [
            'first_name'  => strtolower($validated['first_name']),
            'middle_name' => $validated['middle_name'] ? strtolower($validated['middle_name']) : null,
            'last_name'   => strtolower($validated['last_name']),
            'name_ext'    => $validated['name_ext'] ? strtolower($validated['name_ext']) : null,
            'username'    => $validated['username'], 
            'email'       => strtolower($validated['email']),
            'password'    => Hash::make($validated['password']), 
            'role'        => $validated['role'],
        ];

        User::create($userData);

        return redirect()->route('admin.userManagement')->with('success', 'User account created successfully.');
    }

    public function updateUser(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        $user->first_name  = strtolower($validated['first_name']);
        $user->middle_name = $validated['middle_name'] ? strtolower($validated['middle_name']) : null;
        $user->last_name   = strtolower($validated['last_name']);
        $user->name_ext    = $validated['name_ext'] ? strtolower($validated['name_ext']) : null;

        $user->username = $validated['username'];
        $user->email    = strtolower($validated['email']);
        $user->role     = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()
            ->route('admin.userManagement')
            ->with('success', 'User updated successfully.');
    }

    public function toggleStatus(User $user)
    {
        if (Auth::id() === $user->id) {
            return back()->withErrors(['user' => 'You cannot deactivate your currently active session account.']);
        }

        $user->is_active = !$user->is_active;
        $user->save();

        // 💡 Fully Type-Safe Audit Log using your new Enums
        activity(LogName::ADMIN_ACTION->value)
            ->performedOn($user)
            ->causedBy(Auth::user())
            ->log(LogDescription::userToggled($user->is_active));

        $statusWord = $user->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->route('admin.userManagement')
            ->with('success', "User account has been {$statusWord}.");
    }
}