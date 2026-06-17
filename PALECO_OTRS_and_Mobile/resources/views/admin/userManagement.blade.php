@extends('admin.sidebar')

@section('title', 'Admin Dashboard - PALECO CRM-CWD')

@section('content')
<!-- TEMPORARY DIAGNOSTIC BLOCK — REMOVE ONCE RESOLVED -->
@if ($errors->any())
    <div style="background-color: #fef2f2; border: 1px solid #fca5a5; padding: 16px; border-radius: 8px; margin-bottom: 20px; font-family: sans-serif;">
        <h4 style="margin: 0 0 8px 0; color: #991b1b; font-size: 14px; font-weight: bold;">⚠️ Account Creation Failed:</h4>
        <ul style="margin: 0; padding-left: 20px; color: #b91c1c; font-size: 13px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<div style="font-family: sans-serif; padding: 20px; color: #333;">

    <!-- HEADER SECTION -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h1 style="margin: 0; font-size: 24px;">User Management</h1>
            <p style="margin: 5px 0 0 0; color: #666; font-size: 14px;">Create, update, and deactivate accounts across all roles (Admin, CWD, Foreman, Field Personnel)</p>
        </div>
        <div>
            <button type="button" onclick="toggleUserModal(true)" style="background-color: #00a86b; color: white; border: none; padding: 10px 15px; border-radius: 4px; font-weight: bold; cursor: pointer;">
                + New User
            </button>
        </div>
    </div>

    <!-- TOP METRIC CARDS -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 25px;">
        
        <!-- Admin Card -->
        <div style="border: 1px solid #ddd; padding: 15px; border-radius: 6px; background: #fff;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 20px;">🛡️</span>
                <span style="font-size: 11px; font-weight: bold; background: #f3e5f5; color: #7b1fa2; padding: 2px 8px; border-radius: 10px;">Admin</span>
            </div>
            <div style="font-size: 24px; font-weight: bold; margin-top: 10px;">{{ $counts['admin'] ?? 0 }}</div>
            <div style="font-size: 12px; color: #888;">active accounts</div>
        </div>

        <!-- CWD Officer Card -->
        <div style="border: 1px solid #ddd; padding: 15px; border-radius: 6px; background: #fff;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 20px;">🎧</span>
                <span style="font-size: 11px; font-weight: bold; background: #e3f2fd; color: #1565c0; padding: 2px 8px; border-radius: 10px;">CWD Officer</span>
            </div>
            <div style="font-size: 24px; font-weight: bold; margin-top: 10px;">{{ $counts['cwd'] ?? 0 }}</div>
            <div style="font-size: 12px; color: #888;">active accounts</div>
        </div>

        <!-- Foreman Card -->
        <div style="border: 1px solid #ddd; padding: 15px; border-radius: 6px; background: #fff;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 20px;">👷</span>
                <span style="font-size: 11px; font-weight: bold; background: #fff3e0; color: #ef6c00; padding: 2px 8px; border-radius: 10px;">Foreman</span>
            </div>
            <div style="font-size: 24px; font-weight: bold; margin-top: 10px;">{{ $counts['foreman'] ?? 0 }}</div>
            <div style="font-size: 12px; color: #888;">active accounts</div>
        </div>

        <!-- Field Personnel Card -->
        <div style="border: 1px solid #ddd; padding: 15px; border-radius: 6px; background: #fff;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 20px;">🔧</span>
                <span style="font-size: 11px; font-weight: bold; background: #e8f5e9; color: #2e7d32; padding: 2px 8px; border-radius: 10px;">Field Personnel</span>
            </div>
            <div style="font-size: 24px; font-weight: bold; margin-top: 10px;">{{ $counts['field_personnel'] ?? 0 }}</div>
            <div style="font-size: 12px; color: #888;">active accounts</div>
        </div>

    </div>

    <!-- FILTER BAR (SEARCH & TABS) -->
    <form action="{{ route('admin.userManagement') }}" method="GET" style="display: flex; justify-content: space-between; align-items: center; background: #fff; border: 1px solid #ddd; padding: 10px; border-radius: 6px; margin-bottom: 20px;">
        
        <div style="flex: 1; margin-right: 20px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="🔍 Search by name, username, or email..." style="width: 100%; max-width: 400px; padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
        </div>

        <div style="display: flex; gap: 5px;">
            <a href="{{ route('admin.userManagement', ['role' => 'all', 'search' => request('search')]) }}" style="text-decoration: none; padding: 6px 12px; border-radius: 4px; font-size: 14px; background: {{ !request('role') || request('role') == 'all' ? '#eee' : 'transparent' }}; color: #333;">All</a>
            <a href="{{ route('admin.userManagement', ['role' => 'admin', 'search' => request('search')]) }}" style="text-decoration: none; padding: 6px 12px; border-radius: 4px; font-size: 14px; background: {{ request('role') == 'admin' ? '#eee' : 'transparent' }}; color: #333;">Admin</a>
            <a href="{{ route('admin.userManagement', ['role' => 'cwd', 'search' => request('search')]) }}" style="text-decoration: none; padding: 6px 12px; border-radius: 4px; font-size: 14px; background: {{ request('role') == 'cwd' ? '#eee' : 'transparent' }}; color: #333;">CWD Officer</a>
            <a href="{{ route('admin.userManagement', ['role' => 'foreman', 'search' => request('search')]) }}" style="text-decoration: none; padding: 6px 12px; border-radius: 4px; font-size: 14px; background: {{ request('role') == 'foreman' ? '#eee' : 'transparent' }}; color: #333;">Foreman</a>
            <a href="{{ route('admin.userManagement', ['role' => 'field_personnel', 'search' => request('search')]) }}" style="text-decoration: none; padding: 6px 12px; border-radius: 4px; font-size: 14px; background: {{ request('role') == 'field_personnel' ? '#eee' : 'transparent' }}; color: #333;">Field Personnel</a>
        </div>

        <button type="submit" style="display: none;"></button>
    </form>

    <!-- DATA TABLE SECTION -->
    <div style="background: #fff; border: 1px solid #ddd; border-radius: 6px; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
            <thead>
                <tr style="background: #f8f9fa; border-bottom: 1px solid #ddd; text-transform: uppercase; font-size: 11px; color: #777; letter-spacing: 0.5px;">
                    <th style="padding: 12px 15px;">User</th>
                    <th style="padding: 12px 15px;">Role</th>
                    <th style="padding: 12px 15px;">Team / Shift</th>
                    <th style="padding: 12px 15px;">Last Login</th>
                    <th style="padding: 12px 15px;">Status</th>
                    <th style="padding: 12px 15px; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr style="border-bottom: 1px solid #eee;">
                        <!-- Column 1: User Initials & Metadata Container -->
                        <td style="padding: 15px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: #e0f2f1; color: #00796b; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 12px;">
                                    {{ strtoupper(substr($user->username, 0, 2)) }}
                                </div>
                                <div>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <!-- Safe, Emphasized Username -->
                                        <strong style="color: #111;">{{ $user->username }}</strong>
                                        
                                        <!-- Render styling on the frontend layout side safely -->
                                        @if($user->legal_full_name)
                                            <span style="color: #666; font-size: 13px; font-weight: normal;">
                                                — {{ $user->legal_full_name }}
                                            </span>
                                        @endif
                                    </div>
                                    <div style="font-size: 12px; color: #666;">📧 {{ $user->email }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Column 2: Assigned Dynamic Role Badges -->
                        <td style="padding: 15px;">
                            @php
                                $badgeStyle = match($user->role->value ?? '') {
                                    'admin' => 'background: #f3e5f5; color: #7b1fa2;',
                                    'cwd' => 'background: #e3f2fd; color: #1565c0;',
                                    'foreman' => 'background: #fff3e0; color: #ef6c00;',
                                    default => 'background: #e8f5e9; color: #2e7d32;',
                                };
                                $roleLabel = match($user->role->value ?? '') {
                                    'admin' => 'Admin',
                                    'cwd' => 'CWD Officer',
                                    'foreman' => 'Foreman',
                                    default => 'Field Personnel',
                                };
                            @endphp
                            <span style="font-size: 12px; font-weight: bold; padding: 4px 10px; border-radius: 12px; {{ $badgeStyle }}">
                                {{ $roleLabel }}
                            </span>
                        </td>

                        <!-- Column 3: Team / Shift Structural Placeholder -->
                        <td style="padding: 15px; color: #555;">
                            {{ $user->team_shift ?? '—' }}
                        </td>

                        <!-- Column 4: Last Active Session Timestamp -->
                        <td style="padding: 15px; color: #555;">
                            {{ $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i') : '—' }}
                        </td>

                        <!-- Column 5: Casting-aware Active State Badging -->
                        <td style="padding: 15px;">
                            @if($user->is_active)
                                <span style="font-size: 12px; font-weight: bold; background: #e8f5e9; color: #2e7d32; padding: 4px 10px; border-radius: 12px;">Active</span>
                            @else
                                <span style="font-size: 12px; font-weight: bold; background: #ffebee; color: #c62828; padding: 4px 10px; border-radius: 12px;">Inactive</span>
                            @endif
                        </td>

                        <!-- Column 6: Administrative Layout Trigger Actions -->
                        <td style="padding: 15px; text-align: right; font-size: 18px; color: #666;">
                            <span style="cursor: pointer; margin-right: 10px;" title="Edit Account">✏️</span>
                            <span style="cursor: pointer;" title="Toggle Activity State">👤❌</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 30px; text-align: center; color: #999;">No users found matching your filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- CUSTOM BULLETPROOF PAGINATION NAVIGATION -->
    <div style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 12px 20px; border: 1px solid #ddd; border-radius: 6px; font-family: sans-serif;">
        
        <!-- Total Counts Status Text -->
        <div style="color: #555; font-size: 14px;">
            Showing <strong>{{ $users->firstItem() ?? 0 }}</strong> to <strong>{{ $users->lastItem() ?? 0 }}</strong> of <strong>{{ $users->total() }}</strong> users
        </div>

        <!-- Custom Action Buttons Layout -->
        <div style="display: flex; gap: 8px;">
            <!-- Previous Button Link -->
            @if ($users->onFirstPage())
                <span style="color: #aaa; background: #f5f5f5; border: 1px solid #ddd; padding: 6px 14px; border-radius: 4px; font-size: 13px; cursor: not-allowed;">« Previous</span>
            @else
                <a href="{{ $users->previousPageUrl() }}" style="color: #333; background: #fff; border: 1px solid #ccc; text-decoration: none; padding: 6px 14px; border-radius: 4px; font-size: 13px; font-weight: 500;">« Previous</a>
            @endif

            <!-- Next Button Link -->
            @if ($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}" style="color: #333; background: #fff; border: 1px solid #ccc; text-decoration: none; padding: 6px 14px; border-radius: 4px; font-size: 13px; font-weight: 500;">Next »</a>
            @else
                <span style="color: #aaa; background: #f5f5f5; border: 1px solid #ddd; padding: 6px 14px; border-radius: 4px; font-size: 13px; cursor: not-allowed;">Next »</span>
            @endif
        </div>

    </div>

</div>

<!-- CREATE USER POP-UP MODAL BACKDROP CONTROLLER -->
<div id="create-user-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm transition-opacity duration-200 font-sans">
    
    <!-- Modal Foreground Card Box Container -->
    <div class="bg-white rounded-xl shadow-xl w-full max-w-xl mx-4 overflow-hidden transform transition-all flex flex-col max-h-[90vh]">
        
        <!-- Modal Heading Top Header Navigation Pane -->
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-white sticky top-0 z-10">
            <h3 class="text-base font-bold text-slate-800">Create New User (* means required)</h3>
            <button type="button" onclick="toggleUserModal(false)" class="text-slate-400 hover:text-slate-600 font-medium text-2xl transition-colors leading-none">&times;</button>
        </div>

        <!-- Form Submission Shell Wrap -->
        <form action="{{ route('admin.addUser') }}" method="POST" class="flex flex-col flex-1 overflow-y-auto m-0">
            @csrf
            
            <!-- Modal Internal Core Form Entry Content Body -->
            <div class="p-6 space-y-4 flex-1">
                
                <!-- ROW 1: SPLIT 4-COLUMN LEGAL NAME LAYOUT GRID -->
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Full Name</label>
                    <div class="grid grid-cols-12 gap-2">
                        <div class="col-span-4">
                            <input type="text" name="first_name" required placeholder="First Name *" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 box-border text-slate-800 placeholder-slate-400">
                        </div>
                        <div class="col-span-3">
                            <input type="text" name="middle_name" placeholder="Middle Name" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 box-border text-slate-800 placeholder-slate-400">
                        </div>
                        <div class="col-span-3">
                            <input type="text" name="last_name" required placeholder="Last Name *" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 box-border text-slate-800 placeholder-slate-400">
                        </div>
                        <div class="col-span-2">
                            <input type="text" name="name_ext" placeholder="Ext (Jr.)" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 box-border text-slate-800 placeholder-slate-400">
                        </div>
                    </div>
                </div>

                <!-- ROW 2: ACCOUNT ID CREDENTIALS GRID -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Username *</label>
                        <input type="text" name="username" required placeholder="Enter login username" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 box-border text-slate-800 placeholder-slate-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Email Address *</label>
                        <input type="email" name="email" required placeholder="name@company.com" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 box-border text-slate-800 placeholder-slate-400">
                    </div>
                </div>

                <!-- ROW 3: SECURE ACCESSIBILITY PASSWORDS GRID WITH VISIBILITY TOGGLES -->
                <div class="grid grid-cols-2 gap-4">
                    <!-- Password Field -->
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Password *</label>
                        <div class="relative w-full">
                            <input type="password" id="password-field" name="password" required placeholder="••••••••" minlength="8" class="w-full pl-3 pr-10 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 box-border text-slate-800 placeholder-slate-400">
                            <button type="button" onclick="togglePasswordVisibility('password-field', 'password-toggle-btn')" id="password-toggle-btn" class="absolute right-2 top-1/2 -translate-y-1/2 bg-none border-none text-xs font-bold text-slate-400 hover:text-slate-600 cursor-pointer p-1 uppercase tracking-wider select-none leading-none">Show</button>
                        </div>
                    </div>
                    <!-- Confirm Password Field -->
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Confirm Password *</label>
                        <div class="relative w-full">
                            <input type="password" id="password-confirm-field" name="password_confirmation" required placeholder="••••••••" minlength="8" class="w-full pl-3 pr-10 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 box-border text-slate-800 placeholder-slate-400">
                            <button type="button" onclick="togglePasswordVisibility('password-confirm-field', 'password-confirm-toggle-btn')" id="password-confirm-toggle-btn" class="absolute right-2 top-1/2 -translate-y-1/2 bg-none border-none text-xs font-bold text-slate-400 hover:text-slate-600 cursor-pointer p-1 uppercase tracking-wider select-none leading-none">Show</button>
                        </div>
                    </div>
                </div>

                <!-- ROW 4: ROLE ASSIGNMENT -->
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                        Role Assignment *
                    </label>

                    <select
                        name="role"
                        required
                        class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 box-border text-slate-800 bg-white"
                    >
                        <option value="">-- Select Role --</option>

                        @foreach (\App\Enums\UserRole::cases() as $role)
                            <option
                                value="{{ $role->value }}"
                                {{ old('role') === $role->value ? 'selected' : '' }}
                            >
                                {{ ucwords(str_replace('_', ' ', $role->value)) }}
                            </option>
                        @endforeach
                    </select>

                    <p style="font-size: 11px; color: #64748b; margin-top: 6px;">
                        Select the system role to assign to this account.
                    </p>
                </div>

            </div>

            <!-- MODAL ACTION FOOTER BUTTON UTILITIES -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3 sticky bottom-0 z-10">
                <button type="button" onclick="toggleUserModal(false)" class="px-4 py-2 text-sm font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors cursor-pointer">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 shadow-sm transition-colors cursor-pointer">
                    Create User
                </button>
            </div>
        </form>

    </div>
</div>

<!-- MODAL STATE JAVASCRIPT CONTROLLER -->
<script>
function toggleUserModal(show) {
    const modal = document.getElementById('create-user-modal');
    if (show) {
        modal.classList.remove('hidden');
    } else {
        modal.classList.add('hidden');
    }
}

document.addEventListener("DOMContentLoaded", function() {
    const defaultRole = document.getElementById('role-input').value;
    selectRole(defaultRole);
});

function togglePasswordVisibility(fieldId, buttonId) {
    const passwordInput = document.getElementById(fieldId);
    const toggleButton = document.getElementById(buttonId);
    
    if (passwordInput && toggleButton) {
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleButton.textContent = "Hide";
            toggleButton.classList.remove('text-slate-400');
            toggleButton.classList.add('text-emerald-600');
        } else {
            passwordInput.type = "password";
            toggleButton.textContent = "Show";
            toggleButton.classList.remove('text-emerald-600');
            toggleButton.classList.add('text-slate-400');
        }
    }
}

</script>
@endsection

