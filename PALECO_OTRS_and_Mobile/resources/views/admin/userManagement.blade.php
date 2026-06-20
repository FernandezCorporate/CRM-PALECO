@extends('admin.sidebar')

@section('title', 'Admin Dashboard - PALECO CRM-CWD')

@section('content')
@if (session('success'))
    <div class="bg-emerald-50 border border-emerald-200 p-4 rounded-lg mb-6 flex items-start gap-3 shadow-sm">
        <svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <div>
            <h4 class="text-sm font-bold text-emerald-800 mb-1">Success</h4>
            <p class="text-sm text-emerald-700 m-0">{{ session('success') }}</p>
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="bg-rose-50 border border-rose-200 p-4 rounded-lg mb-6 shadow-sm">
        <div class="flex items-start gap-3 mb-2">
            <svg class="w-5 h-5 text-rose-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <h4 class="text-sm font-bold text-rose-800">Account Action Failed</h4>
        </div>
        <ul class="list-disc pl-10 text-sm text-rose-700 m-0 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

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

    <form action="{{ route('admin.userManagement') }}" method="GET" class="flex flex-col md:flex-row justify-between items-center bg-white border border-slate-200 p-3 rounded-xl mb-6 shadow-sm gap-4">
        <div class="w-full md:w-1/2 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, username, or email..." class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
        </div>

        <div class="flex flex-wrap gap-2 w-full md:w-auto overflow-x-auto">
            <a href="{{ route('admin.userManagement', ['role' => 'all', 'search' => request('search')]) }}" class="px-3 py-1.5 rounded-md text-sm transition-colors {{ !request('role') || request('role') == 'all' ? 'bg-slate-100 text-slate-800 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">All</a>
            <a href="{{ route('admin.userManagement', ['role' => 'admin', 'search' => request('search')]) }}" class="px-3 py-1.5 rounded-md text-sm transition-colors {{ request('role') == 'admin' ? 'bg-slate-100 text-slate-800 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Admin</a>
            <a href="{{ route('admin.userManagement', ['role' => 'cwd', 'search' => request('search')]) }}" class="px-3 py-1.5 rounded-md text-sm transition-colors {{ request('role') == 'cwd' ? 'bg-slate-100 text-slate-800 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">CWD Officer</a>
            <a href="{{ route('admin.userManagement', ['role' => 'foreman', 'search' => request('search')]) }}" class="px-3 py-1.5 rounded-md text-sm transition-colors {{ request('role') == 'foreman' ? 'bg-slate-100 text-slate-800 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Foreman</a>
            <a href="{{ route('admin.userManagement', ['role' => 'field_personnel', 'search' => request('search')]) }}" class="px-3 py-1.5 rounded-md text-sm transition-colors {{ request('role') == 'field_personnel' ? 'bg-slate-100 text-slate-800 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Field Personnel</a>
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
                            @php
                                $badgeClass = match($user->role->value ?? '') {
                                    'admin' => 'bg-purple-100 text-purple-700',
                                    'cwd' => 'bg-blue-100 text-blue-700',
                                    'foreman' => 'bg-orange-100 text-orange-700',
                                    default => 'bg-emerald-100 text-emerald-700',
                                };
                                $roleLabel = match($user->role->value ?? '') {
                                    'admin' => 'Admin',
                                    'cwd' => 'CWD Officer',
                                    'foreman' => 'Foreman',
                                    default => 'Field Personnel',
                                };
                            @endphp
                            <span class="text-[11px] font-bold px-2.5 py-1 rounded-full {{ $badgeClass }}">
                                {{ $roleLabel }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-slate-500">
                            {{ $user->team_shift ?? '—' }}
                        </td>

                        <td class="px-6 py-4 text-slate-500">
                            {{ $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i') : '—' }}
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

    <div class="mt-4 flex flex-col sm:flex-row justify-between items-center bg-white p-4 border border-slate-200 rounded-xl shadow-sm gap-4">
        <div class="text-sm text-slate-500">
            Showing <span class="font-bold text-slate-800">{{ $users->firstItem() ?? 0 }}</span> to <span class="font-bold text-slate-800">{{ $users->lastItem() ?? 0 }}</span> of <span class="font-bold text-slate-800">{{ $users->total() }}</span> users
        </div>

        <div class="flex gap-2">
            @if ($users->onFirstPage())
                <span class="px-4 py-2 text-sm font-medium text-slate-400 bg-slate-50 border border-slate-200 rounded-lg cursor-not-allowed">« Previous</span>
            @else
                <a href="{{ $users->previousPageUrl() }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">« Previous</a>
            @endif

            @if ($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">Next »</a>
            @else
                <span class="px-4 py-2 text-sm font-medium text-slate-400 bg-slate-50 border border-slate-200 rounded-lg cursor-not-allowed">Next »</span>
            @endif
        </div>
    </div>

</div>

<div id="user-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm transition-opacity duration-200 font-sans">
    
    <div class="bg-white rounded-xl shadow-xl w-full max-w-xl mx-4 overflow-hidden transform transition-all flex flex-col max-h-[90vh]">
        
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-white sticky top-0 z-10">
            <div>
                <h3 id="modal-title" class="text-base font-bold text-slate-800">Create New User</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">Fields marked with an asterisk (<span class="text-rose-500">*</span>) are required.</p>
            </div>
            <button type="button" onclick="closeUserModal()" class="text-slate-400 hover:text-slate-600 font-medium text-2xl transition-colors leading-none pb-2">&times;</button>
        </div>

        <form id="user-form" action="{{ route('admin.addUser') }}" method="POST" class="flex flex-col flex-1 overflow-y-auto m-0">
            @csrf
            
            <div id="method-container"></div>
            
            <div class="p-6 space-y-5 flex-1">
                
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Full Name</label>
                    <div class="grid grid-cols-12 gap-2">
                        <div class="col-span-12 sm:col-span-4">
                            <input type="text" id="user_first_name" name="first_name" required placeholder="First Name *" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-slate-800 placeholder-slate-400">
                        </div>
                        <div class="col-span-12 sm:col-span-3">
                            <input type="text" id="user_middle_name" name="middle_name" placeholder="Middle Name" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-slate-800 placeholder-slate-400">
                        </div>
                        <div class="col-span-12 sm:col-span-3">
                            <input type="text" id="user_last_name" name="last_name" required placeholder="Last Name *" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-slate-800 placeholder-slate-400">
                        </div>
                        <div class="col-span-12 sm:col-span-2">
                            <input type="text" id="user_name_ext" name="name_ext" placeholder="Ext (Jr.)" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-slate-800 placeholder-slate-400">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Username <span class="text-rose-500">*</span></label>
                        <input type="text" id="user_username" name="username" required placeholder="Enter login username" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-slate-800 placeholder-slate-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Email Address <span class="text-rose-500">*</span></label>
                        <input type="email" id="user_email" name="email" required placeholder="name@company.com" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-slate-800 placeholder-slate-400">
                    </div>
                </div>

                <div class="bg-slate-50 p-4 rounded-lg border border-slate-100">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-baseline mb-3 gap-1">
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider m-0">Security</label>
                        <span id="password-hint" class="text-[11px] text-slate-500">Minimum 8 characters. Both fields required.</span>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="relative w-full">
                            <input type="password" id="password-field" name="password" required placeholder="Password *" minlength="8" class="w-full pl-3 pr-12 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-slate-800 placeholder-slate-400">
                            <button type="button" onclick="togglePasswordVisibility('password-field', 'password-toggle-btn')" id="password-toggle-btn" class="absolute right-2 top-1/2 -translate-y-1/2 bg-transparent border-none text-[10px] font-bold text-slate-400 hover:text-slate-600 cursor-pointer p-1 uppercase tracking-wider select-none">Show</button>
                        </div>
                        <div class="relative w-full">
                            <input type="password" id="password-confirm-field" name="password_confirmation" required placeholder="Confirm Password *" minlength="8" class="w-full pl-3 pr-12 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-slate-800 placeholder-slate-400">
                            <button type="button" onclick="togglePasswordVisibility('password-confirm-field', 'password-confirm-toggle-btn')" id="password-confirm-toggle-btn" class="absolute right-2 top-1/2 -translate-y-1/2 bg-transparent border-none text-[10px] font-bold text-slate-400 hover:text-slate-600 cursor-pointer p-1 uppercase tracking-wider select-none">Show</button>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Role Assignment <span class="text-rose-500">*</span></label>
                    <select id="user_role" name="role" required class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-slate-800 bg-white appearance-none cursor-pointer">
                        <option value="">-- Select Role --</option>
                        @foreach (\App\Enums\UserRole::cases() as $role)
                            <option value="{{ $role->value }}">{{ ucwords(str_replace('_', ' ', $role->value)) }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3 sticky bottom-0 z-10">
                <button type="button" onclick="closeUserModal()" class="px-4 py-2 text-sm font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-100 transition-colors shadow-sm">
                    Cancel
                </button>
                <button type="submit" id="submit-btn" class="px-4 py-2 text-sm font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 shadow-sm transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Create User
                </button>
            </div>
        </form>

    </div>
</div>

@endsection