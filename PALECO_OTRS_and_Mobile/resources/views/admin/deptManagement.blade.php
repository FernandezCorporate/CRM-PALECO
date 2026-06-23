@extends('admin.sidebar')
@section('title', 'Department Management - PALECO CRM-CWD')

@section('content')

@include('admin.prompts.admin-prompt')

<div class="text-slate-800">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 m-0">Department Management</h1>
            <p class="text-sm text-slate-500 mt-1">Admin-only — manage departments and their field teams.</p>
        </div>
        <a href={{ route('admin.addDeptForm') }} class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-semibold text-sm transition-colors shadow-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Add Department
        </a>
    </div>

    <div class="bg-emerald-50 border border-emerald-200 p-4 rounded-lg mb-8 flex items-start gap-3 shadow-sm">
        <svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
        </svg>
        <div>
            <h4 class="text-sm font-bold text-slate-800 mb-1">Admin privilege</h4>
            <p class="text-sm text-slate-500 m-0">Click a department card to view and manage its sub-teams. Only IT Admins can reassign tickets between departments.</p>
        </div>
    </div>

    <h2 class="text-xl font-bold text-slate-900 mb-4">Departments</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        
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
                    </div>
                    
                    <div class="space-y-3 mb-6">
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

                <a href="javascript:void(0)" onclick="openTeamViewModal({{ $department->id }})" class="block px-6 py-3 bg-slate-50/50 border-t border-slate-100 text-[13px] font-semibold text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 transition-colors group">
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
</div>

@endsection