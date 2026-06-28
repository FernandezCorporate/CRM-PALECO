@extends('admin.base.sidebar')
@section('title', isset($user) ? 'Edit User - PALECO CRM-CWD' : 'New User - PALECO CRM-CWD')

@section('content')

@include('admin.prompts.admin-prompt')

<div class="max-w-4xl text-slate-800 pb-12">
    
    <div class="mb-8">
        <a href="{{ route('admin.userManagement') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-emerald-600 transition-colors mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to User List
        </a>
        <h1 class="text-2xl font-bold text-slate-900 m-0">
            {{ isset($user) ? 'Edit User Account' : 'Add New User' }}
        </h1>
    </div>

    <form action="{{ isset($user) ? route('admin.updateUser', $user->id) : route('admin.addUser') }}" method="POST" class="space-y-10" autocomplete="off" id="user-form">
        @csrf
        @if(isset($user)) @method('PUT') @endif
        
        <div>
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-5 border-b border-slate-200 pb-2">Personal Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1.5">First Name *</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $user->first_name ?? '') }}" required maxlength="255" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg">
                    @error('first_name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1.5">Middle Name</label>
                    <input type="text" name="middle_name" value="{{ old('middle_name', $user->middle_name ?? '') }}" maxlength="255" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg">
                </div>
                <div class="md:col-span-2 md:col-start-1">
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1.5">Last Name *</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $user->last_name ?? '') }}" required maxlength="255" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg">
                    @error('last_name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1.5">Ext (Jr, Sr)</label>
                    <input type="text" name="name_ext" value="{{ old('name_ext', $user->name_ext ?? '') }}" maxlength="10" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg">
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-5 border-b border-slate-200 pb-2">Account Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1.5">System Username *</label>
                    <input type="text" name="username" value="{{ old('username', $user->username ?? '') }}" required maxlength="255" autocomplete="off" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg">
                    @error('username') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1.5">Contact Number *</label>
                    <input type="text" name="contact" value="{{ old('contact', $user->contact ?? '') }}" required pattern="^(09|\+639)\d{9}$" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg">
                    @error('contact') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1.5">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" maxlength="255" autocomplete="off" class="w-full md:w-[calc(50%-12px)] px-3 py-2 text-sm border border-slate-200 rounded-lg">
                    @error('email') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                @if(!isset($user))
                    <div class="pt-2">
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-1.5">Initial Password *</label>
                        <div class="relative">
                            <input type="password" id="create-password" name="password" required minlength="8" class="w-full pl-3 pr-10 py-2 text-sm border border-slate-200 rounded-lg" >
                            <button type="button" onclick="togglePasswordVisibility('create-password', 'create-eye-icon')" class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-400">
                                <svg id="create-eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                        </div>
                        @error('password') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="pt-2">
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-1.5">Confirm Password *</label>
                        <div class="relative">
                            <input type="password" id="confirm-password" name="password_confirmation" required minlength="8" class="w-full pl-3 pr-10 py-2 text-sm border border-slate-200 rounded-lg">
                            <button type="button" onclick="togglePasswordVisibility('confirm-password', 'confirm-eye-icon')" class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-400">
                                <svg id="confirm-eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div>
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-5 border-b border-slate-200 pb-2">Operational Assignment</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1.5">System Role *</label>
                    <select name="role" required class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg">
                        <option value="">-- Select a Role --</option>
                        @foreach($userRoles as $role)
                            <option value="{{ $role->value }}" {{ old('role', isset($user) ? $user->role?->value : '') == $role->value ? 'selected' : '' }}>
                                {{ $role->label() }}
                            </option>
                        @endforeach
                    </select>
                    @error('role') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1.5">Department</label>
                    <select name="department_id" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg">
                        <option value="">-- Unassigned --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id', $user->department_id ?? '') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->dept_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="pt-4 flex items-center gap-4">
            <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors shadow-sm">
                {{ isset($user) ? 'Save Changes' : 'Create User' }}
            </button>
            <a href="{{ route('admin.userManagement') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-800 transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection