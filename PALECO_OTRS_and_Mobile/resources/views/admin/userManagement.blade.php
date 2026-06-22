@extends('admin.sidebar')

@section('title', 'User Management')

@section('content')

@include('admin.prompts.admin-prompt')

<div class="text-slate-800">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 m-0">User Management</h1>
            <p class="text-sm text-slate-500 mt-1">Create, update, and deactivate accounts across all roles.</p>
        </div>
        <button type="button" onclick="openUserModal()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-semibold text-sm transition-colors shadow-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            New User
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-slate-200 p-4 rounded-xl shadow-sm">
            <div class="flex justify-between items-center mb-2">
                <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"></path></svg>
                <span class="text-[10px] font-bold uppercase tracking-wider bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full">Admin</span>
            </div>
            <div class="text-2xl font-bold text-slate-800">{{ $counts['admin'] ?? 0 }}</div>
            <div class="text-xs text-slate-500 mt-1">active accounts</div>
        </div>

        <div class="bg-white border border-slate-200 p-4 rounded-xl shadow-sm">
            <div class="flex justify-between items-center mb-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"></path></svg>
                <span class="text-[10px] font-bold uppercase tracking-wider bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">CWD</span>
            </div>
            <div class="text-2xl font-bold text-slate-800">{{ $counts['cwd'] ?? 0 }}</div>
            <div class="text-xs text-slate-500 mt-1">active accounts</div>
        </div>

        <div class="bg-white border border-slate-200 p-4 rounded-xl shadow-sm">
            <div class="flex justify-between items-center mb-2">
                <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z"></path></svg>
                <span class="text-[10px] font-bold uppercase tracking-wider bg-orange-100 text-orange-700 px-2 py-0.5 rounded-full">Foreman</span>
            </div>
            <div class="text-2xl font-bold text-slate-800">{{ $counts['foreman'] ?? 0 }}</div>
            <div class="text-xs text-slate-500 mt-1">active accounts</div>
        </div>

        <div class="bg-white border border-slate-200 p-4 rounded-xl shadow-sm">
            <div class="flex justify-between items-center mb-2">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"></path></svg>
                <span class="text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full">Field</span>
            </div>
            <div class="text-2xl font-bold text-slate-800">{{ $counts['field_personnel'] ?? 0 }}</div>
            <div class="text-xs text-slate-500 mt-1">active accounts</div>
        </div>
    </div>

    <form action="{{ route('admin.userManagement') }}" method="GET" class="flex flex-col lg:flex-row lg:items-end bg-white border border-slate-200 p-4 rounded-xl mb-6 shadow-sm gap-4">
        
        <div class="w-full lg:flex-1 shrink-0">
            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5 ml-1">
                Search Users
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, username, or email..." class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
            </div>
        </div>

        <div class="w-full lg:w-48 shrink-0">
            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5 ml-1">
                Filter By
            </label>
            <select name="role" onchange="this.form.submit()" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-slate-700 bg-white">
                <option value="all" {{ request('role', 'all') == 'all' ? 'selected' : '' }}>All Roles</option>
                @foreach(\App\Enums\UserRole::cases() as $roleOption)
                    <option value="{{ $roleOption->value }}" {{ request('role') == $roleOption->value ? 'selected' : '' }}>
                        {{ $roleOption->label() }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="w-full lg:w-48 shrink-0">
            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5 ml-1">
                Order By
            </label>
            <select name="sort" onchange="this.form.submit()" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-slate-700 bg-white">
                @foreach(\App\Enums\UserSort::cases() as $sortOption)
                    <option value="{{ $sortOption->value }}" {{ request('sort') == $sortOption->value ? 'selected' : '' }}>
                        {{ $sortOption->label() }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <button type="submit" class="hidden"></button>
    </form>

    <div class="bg-white border border-slate-200 rounded-xl overflow-x-auto shadow-sm">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr class="text-xs uppercase tracking-wider text-slate-500 font-semibold">
                    <th class="px-6 py-4">User</th>
                    <th class="px-6 py-4">Role</th>
                    <th class="px-6 py-4">Team / Shift</th>
                    <th class="px-6 py-4">Last Login</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($users as $user)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs shrink-0">
                                    {{ strtoupper(substr($user->username, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-slate-900">{{ $user->username }}</span>
                                        @if($user->legal_full_name)
                                            <span class="text-slate-500 text-xs hidden md:inline">— {{ $user->legal_full_name }}</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-slate-500 mt-0.5 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        {{ $user->email }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            @if($user->role)
                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full {{ $user->role->badgeClasses() }}">
                                    {{ $user->role->label() }}
                                </span>
                            @else
                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">
                                    Unassigned
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            @if($user->department)
                                <div class="font-medium text-slate-800">{{ $user->department->dept_name }}</div>
                            @else
                                <div class="text-slate-400">—</div>
                            @endif
                            
                            <div class="text-[11px] text-slate-500 mt-0.5 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $user->shift_start ? \Carbon\Carbon::parse($user->shift_start)->format('g:i A') : '--:--' }} 
                                to 
                                {{ $user->shift_end ? \Carbon\Carbon::parse($user->shift_end)->format('g:i A') : '--:--' }}
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                            @if($user->last_login_at)
                                <span class="text-slate-800 font-medium">{{ $user->last_login_at->format('M d, Y') }}</span>
                                <br>
                                <span class="text-[11px] text-slate-400">{{ $user->last_login_at->format('h:i A') }}</span>
                            @else
                                <span class="text-slate-400 italic">Never logged in</span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            @if($user->is_active)
                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-green-100 text-green-700 flex items-center gap-1 w-max">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Active
                                </span>
                            @else
                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-rose-100 text-rose-700 flex items-center gap-1 w-max">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Inactive
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-right">
                            <button type="button" onclick="openUserModal({{ $user->toJson() }})" class="text-slate-400 hover:text-blue-600 transition-colors mr-3" title="Edit Account">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <form action="{{ route('admin.toggleStatus', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to {{ $user->is_active ? 'deactivate' : 'activate' }} this account?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-slate-400 transition-colors {{ $user->is_active ? 'hover:text-rose-600' : 'hover:text-green-600' }}" title="{{ $user->is_active ? 'Deactivate Account' : 'Activate Account' }}">
                                    @if($user->is_active)
                                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @else
                                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @endif
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                No users found matching your filters.
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @include('admin.paginations.user-pagination')
</div>

@include('admin.forms.user-modal')

@endsection