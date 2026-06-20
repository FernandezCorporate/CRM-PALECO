@extends('admin.sidebar')

@section('title', 'Department Management')

@section('content')

<div class="text-slate-800">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 m-0">Department Management</h1>
            <p class="text-sm text-slate-500 mt-1">Admin-only — manage departments, their field teams, and reassign tickets.</p>
        </div>
        <button type="button" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-semibold text-sm transition-colors shadow-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Add Department
        </button>
    </div>

    <div class="bg-emerald-50 border border-emerald-200 p-4 rounded-lg mb-8 flex items-start gap-3 shadow-sm">
        <svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
        </svg>
        <div>
            <h4 class="text-sm font-bold text-slate-800 mb-1">Admin privilege</h4>
            <p class="text-sm text-slate-500 m-0">Each department is led by one foreman. Click a department card to view and manage its sub-teams. Only IT Admins can reassign tickets between departments.</p>
        </div>
    </div>

    <h2 class="text-xl font-bold text-slate-900 mb-4">Departments</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse ($departments as $department)
            <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow relative flex flex-col min-h-[160px]">
                
                <h3 class="text-lg font-bold text-slate-900 mb-2">{{ $department->dept_name }}</h3>
                
                <p class="text-[13px] text-slate-500 mb-6 line-clamp-2">{{ $department->dept_description ?? 'No description available.' }}</p>
                
                <div class="mt-auto pt-4 border-t border-slate-100">
                    <div class="flex items-start gap-2 text-[13px] text-slate-700">
                        <svg class="w-4 h-4 text-slate-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="flex-1">
                            <span class="text-slate-500">Foreman:</span> 
                            @if($department->foremen_list)
                                <span class="font-medium text-slate-900">{{ $department->foremen_list }}</span>
                            @else
                                <span class="text-slate-400 italic">Unassigned</span>
                            @endif
                        </span>
                    </div>
                </div>

            </div>
        @empty
            <div class="col-span-full bg-white border border-slate-200 rounded-xl p-8 text-center text-slate-500 shadow-sm">
                No departments found. Click "Add Department" to create your first one.
            </div>
        @endforelse
    </div>

</div>

@endsection