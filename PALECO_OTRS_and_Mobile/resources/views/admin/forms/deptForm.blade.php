@extends('admin.base.sidebar')
@section('title', isset($dept) ? 'Edit Department - PALECO CRM-CWD' : 'New Department - PALECO CRM-CWD')

@section('content')

@include('admin.prompts.admin-prompt')

<div class="max-w-3xl text-slate-800 pb-12">
    
    <!-- Header & Back Button -->
    <div class="mb-8">
        <a href="{{ route('admin.deptManagement') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-emerald-600 transition-colors mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Departments
        </a>
        <h1 class="text-2xl font-bold text-slate-900 m-0">
            {{ isset($dept) ? 'Edit Department' : 'Add New Department' }}
        </h1>
        <p class="text-sm text-slate-500 mt-1">
            {{ isset($dept) ? 'Update the details for ' . $dept->dept_name . '.' : 'Create a new department to group field teams and personnel.' }}
        </p>
    </div>

    <!-- The Unified Form -->
    <form action="{{ isset($dept) ? route('admin.updateDept', $dept->id) : route('admin.addDept') }}" method="POST" class="space-y-8" autocomplete="off">
        @csrf
        @if(isset($dept))
            @method('PUT')
        @endif
        
        <div>
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-5 border-b border-slate-200 pb-2">Department Details</h3>
            
            <div class="space-y-6">
                <!-- Department Name -->
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1.5">Department Name *</label>
                    <input type="text" name="dept_name" value="{{ old('dept_name', $dept->dept_name ?? '') }}" required maxlength="255" placeholder="e.g. Technical Services Department" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 bg-white shadow-sm placeholder-slate-300">
                    @error('dept_name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Department Description -->
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1.5">Description</label>
                    <textarea name="dept_description" rows="4" placeholder="Briefly describe the responsibilities of this department..." class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 bg-white shadow-sm placeholder-slate-300 resize-y">{{ old('dept_description', $dept->dept_description ?? '') }}</textarea>
                    @error('dept_description') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Submit Footer -->
        <div class="pt-4 flex items-center gap-4">
            <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors shadow-sm">
                {{ isset($dept) ? 'Save Changes' : 'Create Department' }}
            </button>
            <a href="{{ route('admin.deptManagement') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-800 transition-colors">
                Cancel
            </a>
        </div>
        
    </form>
</div>

@endsection