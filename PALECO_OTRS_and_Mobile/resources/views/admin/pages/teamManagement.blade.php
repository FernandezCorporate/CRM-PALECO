@extends('admin.base.sidebar')
@section('title', 'Field Teams - PALECO CRM-CWD')

@section('content')

@include('admin.prompts.admin-prompt')

<div class="text-slate-800 pb-12">
    
    <div class="mb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 m-0">Field Teams</h1>
            <p class="text-sm text-slate-500 mt-1">Manage field teams, shift schedules, and personnel assignments across all departments.</p>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="flex items-center bg-slate-100 p-1 rounded-lg border border-slate-200">
                <button id="btn-card-view" onclick="window.switchView('card')" class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors text-slate-500 hover:text-slate-700 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span class="hidden md:inline">Cards</span>
                </button>
                <button id="btn-table-view" onclick="window.switchView('table')" class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors text-slate-500 hover:text-slate-700 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    <span class="hidden md:inline">Table</span>
                </button>
            </div>

            <a href="#" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-semibold text-sm transition-colors shadow-sm flex items-center gap-2 h-fit">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Add New Team
            </a>
        </div>
    </div>

    <form action="{{ route('admin.teamManagement') }}" method="GET" class="flex flex-col lg:flex-row lg:items-end bg-white border border-slate-200 p-4 rounded-xl mb-6 shadow-sm gap-4">
        
        <div class="w-full lg:flex-1 shrink-0">
            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5 ml-1">
                Search Teams
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by team name..." class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
            </div>
        </div>

        <div class="w-full lg:w-64 shrink-0">
            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5 ml-1">
                Filter by Department
            </label>
            <select name="department" onchange="this.form.submit()" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-slate-700 bg-white">
                <option value="all" {{ request('department', 'all') == 'all' ? 'selected' : '' }}>All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ request('department') == $dept->id ? 'selected' : '' }}>
                        {{ $dept->dept_name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <button type="submit" class="hidden"></button>
    </form>

    <div id="card-view-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-4">
        @forelse($teams as $team)
            @php
                $leader = $team->users->where('pivot.role', \App\Enums\MemberRole::LEADER->value)->first();
                $memberCount = $team->users->where('pivot.role', \App\Enums\MemberRole::MEMBER->value)->count();
            @endphp

            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden flex flex-col hover:shadow-md transition-shadow">
                <div class="p-5 border-b border-slate-100 flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">{{ $team->team_name }}</h3>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mt-1">{{ $team->department->dept_name }}</p>
                        
                        <div class="flex items-center gap-1.5 mt-2.5 text-xs font-medium text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-md w-max">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $team->shift_start ? \Carbon\Carbon::parse($team->shift_start)->format('g:i A') : '--:--' }} - 
                            {{ $team->shift_end ? \Carbon\Carbon::parse($team->shift_end)->format('g:i A') : '--:--' }}
                        </div>
                    </div>
                    
                    <a href="#" class="text-slate-400 hover:text-emerald-600 transition-colors p-1" title="Edit Team">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </a>
                </div>

                <div class="p-5 flex-1 bg-slate-50/50">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-4">Team Composition</p>
                    <div class="mb-4">
                        <p class="text-xs font-semibold text-slate-700 mb-2">Team Leader</p>
                        @if($leader)
                            <div class="flex items-center gap-2 text-sm text-slate-800 bg-white p-2 border border-slate-200 rounded-lg">
                                <div class="w-7 h-7 rounded-full bg-orange-100 text-orange-700 flex items-center justify-center font-bold text-[10px] shrink-0">
                                    {{ strtoupper(substr($leader->first_name ?? $leader->username, 0, 1)) }}
                                </div>
                                <div class="truncate">
                                    <span class="font-semibold">{{ $leader->first_name }} {{ $leader->last_name }}</span>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center gap-2 text-sm text-slate-400 bg-slate-100 p-2 border border-slate-200 rounded-lg border-dashed">
                                <span class="italic">No leader assigned</span>
                            </div>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-700 mb-2">Field Personnel</p>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <span class="text-sm font-medium text-slate-600">{{ $memberCount }} active members</span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('admin.teamMemberManagement', $team->id) }}" class="block px-6 py-3 bg-white border-t border-slate-100 text-[13px] font-semibold text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 transition-colors group">
                    <div class="flex justify-between items-center">
                        <span>Manage Personnel</span>
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-span-full py-12 bg-white border border-slate-200 rounded-xl shadow-sm text-center">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <h3 class="text-lg font-bold text-slate-800 mb-1">No Teams Found</h3>
                <p class="text-sm text-slate-500">There are no teams matching your criteria.</p>
            </div>
        @endforelse
    </div>

    <div id="table-view-container" class="hidden bg-white border border-slate-200 rounded-xl overflow-x-auto shadow-sm mb-4">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr class="text-xs uppercase tracking-wider text-slate-500 font-semibold">
                    <th class="px-6 py-4">Team & Department</th>
                    <th class="px-6 py-4">Operating Shift</th>
                    <th class="px-6 py-4">Team Leader</th>
                    <th class="px-6 py-4">Members</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($teams as $team)
                    @php
                        $leader = $team->users->where('pivot.role', \App\Enums\MemberRole::LEADER->value)->first();
                        $memberCount = $team->users->where('pivot.role', \App\Enums\MemberRole::MEMBER->value)->count();
                    @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">{{ $team->team_name }}</div>
                            <div class="text-[11px] font-medium text-slate-500 uppercase tracking-wider mt-0.5">{{ $team->department->dept_name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-slate-50 border border-slate-200 rounded-[4px] text-[11px] font-medium text-slate-700">
                                {{ $team->shift_start ? \Carbon\Carbon::parse($team->shift_start)->format('g:i A') : '--:--' }} - 
                                {{ $team->shift_end ? \Carbon\Carbon::parse($team->shift_end)->format('g:i A') : '--:--' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($leader)
                                <div class="flex items-center gap-2 text-sm text-slate-800">
                                    <div class="w-6 h-6 rounded-full bg-orange-100 text-orange-700 flex items-center justify-center font-bold text-[9px] shrink-0">
                                        {{ strtoupper(substr($leader->first_name ?? $leader->username, 0, 1)) }}
                                    </div>
                                    <span class="font-medium">{{ $leader->first_name }} {{ $leader->last_name }}</span>
                                </div>
                            @else
                                <span class="text-xs text-slate-400 italic">Unassigned</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-slate-600 font-medium">{{ $memberCount }} active</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="#" class="text-slate-400 hover:text-emerald-600 transition-colors mr-3" title="Edit Team">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </a>
                            <a href="{{ route('admin.teamMemberManagement', $team->id) }}" class="text-slate-400 hover:text-sky-600 transition-colors ml-2" title="Manage Personnel">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">There are no teams matching your criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @include('admin.paginations.shared-pagination', ['paginator' => $teams, 'itemName' => 'teams'])
</div>

@endsection