@extends('admin.sidebar')
@section('title', 'Department Management - PALECO CRM-CWD')

@section('content')

@include('admin.prompts.admin-prompt')

<div class="text-slate-800 pb-12">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 m-0">Department Management</h1>
            <p class="text-sm text-slate-500 mt-1">Admin-only — manage departments and their field teams.</p>
        </div>
        <div class="flex items-center gap-4">
            
            <div class="flex items-center bg-slate-100 p-1 rounded-lg border border-slate-200">
                <button id="btn-card-view" onclick="switchView('card')" class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors text-slate-500 hover:text-slate-700 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span class="hidden md:inline">Cards</span>
                </button>
                <button id="btn-table-view" onclick="switchView('table')" class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors text-slate-500 hover:text-slate-700 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    <span class="hidden md:inline">Table</span>
                </button>
            </div>

            <a href={{ route('admin.addDeptForm') }} class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-semibold text-sm transition-colors shadow-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Add Department
            </a>
        </div>
    </div>

    <div class="bg-emerald-50 border border-emerald-200 p-4 rounded-lg mb-8 flex items-start gap-3 shadow-sm">
        <svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
        </svg>
        <div>
            <h4 class="text-sm font-bold text-slate-800 mb-1">Admin privilege</h4>
            <p class="text-sm text-slate-500 m-0">Click a department to view and manage its sub-teams. Only IT Admins can reassign tickets between departments.</p>
        </div>
    </div>

    <form action="{{ route('admin.deptManagement') }}" method="GET" class="flex flex-col lg:flex-row lg:items-end bg-white border border-slate-200 p-4 rounded-xl mb-6 shadow-sm gap-4">
        
        <div class="w-full shrink-0">
            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5 ml-1">
                Search Departments
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by department name or description..." class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
            </div>
        </div>
        
        <button type="submit" class="hidden"></button>
    </form>

    <div id="card-view-container" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-4">
        @forelse ($departments as $department)
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition-shadow flex flex-col min-h-[160px] overflow-hidden">
                <div class="p-6 flex-1 flex flex-col">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-lg font-bold text-slate-900">{{ $department->dept_name }}</h3>
                        <a href="{{ route('admin.updateDeptForm', $department->id) }}" class="text-slate-400 hover:text-emerald-600 transition-colors p-1 shrink-0" title="Edit Department">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </a>
                    </div>
                    <p class="text-[13px] text-slate-500 mb-5 line-clamp-2">{{ $department->dept_description ?? 'No description.' }}</p>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center gap-2.5 text-slate-700">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <span class="text-[13px] font-medium">
                                {{ $department->total_teams }} {{ Str::plural('team', $department->total_teams) }} &bull; 
                                {{ $department->total_personnel }} personnel &bull; 
                                {{ $department->total_foremen }} foremen
                            </span>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-slate-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="flex flex-wrap gap-1.5">
                                @forelse($department->unique_shifts as $shift)
                                    <span class="px-2 py-0.5 bg-slate-50 border border-slate-100 rounded-[4px] text-[11px] font-medium text-slate-700">
                                        {{ $shift }}
                                    </span>
                                @empty
                                    <span class="text-[12px] text-slate-400 italic mt-0.5">No shifts scheduled</span>
                                @endforelse
                            </div>
                        </div>
                    </div>                
                </div>

                <a href="{{ route('admin.teamManagement', $department->id) }}" class="block px-6 py-3 bg-slate-50/50 border-t border-slate-100 text-[13px] font-semibold text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 transition-colors group">
                    <div class="flex justify-between items-center">
                        <span>View teams</span>
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-span-full text-center text-slate-500 py-8 bg-white border border-slate-200 rounded-xl shadow-sm">
                No departments found. Click "Add Department" to create your first one.
            </div>
        @endforelse
    </div>

    <div id="table-view-container" class="hidden bg-white border border-slate-200 rounded-xl overflow-x-auto shadow-sm mb-4">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr class="text-xs uppercase tracking-wider text-slate-500 font-semibold">
                    <th class="px-6 py-4">Department Name</th>
                    <th class="px-6 py-4">Personnel Breakdown</th>
                    <th class="px-6 py-4">Active Shifts</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($departments as $department)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">{{ $department->dept_name }}</div>
                            <div class="text-[12px] text-slate-500 w-64 truncate" title="{{ $department->dept_description }}">{{ $department->dept_description ?? 'No description.' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-[12px] text-slate-700 font-medium">
                                {{ $department->total_teams }} Teams &bull; {{ $department->total_personnel }} Field &bull; {{ $department->total_foremen }} Foremen
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-normal min-w-[200px]">
                            <div class="flex flex-wrap gap-1.5">
                                @forelse($department->unique_shifts as $shift)
                                    <span class="px-2 py-0.5 bg-slate-50 border border-slate-100 rounded-[4px] text-[11px] font-medium text-slate-700">
                                        {{ $shift }}
                                    </span>
                                @empty
                                    <span class="text-[11px] text-slate-400 italic">None</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.updateDeptForm', $department->id) }}" class="text-slate-400 hover:text-emerald-600 transition-colors mr-3" title="Edit Department">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </a>
                            <a href="{{ route('admin.teamManagement', $department->id) }}" class="text-slate-400 hover:text-sky-600 transition-colors ml-2" title="View Teams">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-500">No departments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @include('admin.paginations.shared-pagination', ['paginator' => $departments, 'itemName' => 'departments'])
</div>

@endsection