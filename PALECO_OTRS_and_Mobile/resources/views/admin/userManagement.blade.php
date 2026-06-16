@extends('admin.sidebar')

@section('title', 'Admin Dashboard - PALECO CRM-CWD')

@section('content')
<div style="font-family: sans-serif; padding: 20px; color: #333;">

    <!-- HEADER SECTION -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h1 style="margin: 0; font-size: 24px;">User Management</h1>
            <p style="margin: 5px 0 0 0; color: #666; font-size: 14px;">Create, update, and deactivate accounts across all roles (Admin, CWD, Foreman, Field Personnel)</p>
        </div>
        <div>
            <button type="button" style="background-color: #00a86b; color: white; border: none; padding: 10px 15px; border-radius: 4px; font-weight: bold; cursor: pointer;">
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

</div>
@endsection
