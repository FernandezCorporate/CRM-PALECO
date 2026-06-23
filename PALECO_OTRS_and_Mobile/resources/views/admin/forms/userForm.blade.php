@extends('admin.sidebar')
@section('title', 'New User - PALECO CRM-CWD')

@section('content')

@include('admin.prompts.admin-prompt')

<div class="max-w-4xl text-slate-800 pb-12">
    
    <div class="mb-8">
        <a href="{{ route('admin.userManagement') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-emerald-600 transition-colors mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to User List
        </a>
        <h1 class="text-2xl font-bold text-slate-900 m-0">Add New User</h1>
        <p class="text-sm text-slate-500 mt-1">Create a new system account and assign their operational roles.</p>
    </div>

    <form action="{{ route('admin.addUser') }}" method="POST" class="space-y-10" autocomplete="off">
        @csrf
        
        <div>
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-5 border-b border-slate-200 pb-2">Personal Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1.5">First Name *</label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" required maxlength="255" placeholder="e.g. Juan" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 bg-white shadow-sm placeholder-slate-300">
                    @error('first_name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1.5">M.I.</label>
                    <input type="text" name="middle_name" value="{{ old('middle_name') }}" maxlength="255" placeholder="e.g. M" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 bg-white shadow-sm placeholder-slate-300">
                </div>
                <div class="md:col-span-2 md:col-start-1">
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1.5">Last Name *</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" required maxlength="255" placeholder="e.g. Dela Cruz" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 bg-white shadow-sm placeholder-slate-300">
                    @error('last_name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1.5">Ext (Jr, Sr)</label>
                    <input type="text" name="name_ext" value="{{ old('name_ext') }}" maxlength="10" placeholder="e.g. Jr." class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 bg-white shadow-sm placeholder-slate-300">
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-5 border-b border-slate-200 pb-2">Account Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1.5">System Username *</label>
                    <input type="text" name="username" value="{{ old('username') }}" required maxlength="255" autocomplete="off" placeholder="e.g. jdelacruz" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 bg-white shadow-sm placeholder-slate-300">
                    @error('username') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1.5">Contact Number *</label>
                    <input type="text" name="contact" value="{{ old('contact') }}" required pattern="^(09|\+639)\d{9}$" maxlength="13" title="Must start with 09 or +639 followed by 9 digits" placeholder="e.g. 09123456789" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 bg-white shadow-sm placeholder-slate-300">
                    @error('contact') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1.5">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" maxlength="255" autocomplete="off" placeholder="e.g. juan.delacruz@example.com" class="w-full md:w-[calc(50%-12px)] px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 bg-white shadow-sm placeholder-slate-300">
                    @error('email') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="pt-2">
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1.5">Initial Password *</label>
                    <div class="relative">
                        <input type="password" id="create-password" name="password" required minlength="8" autocomplete="new-password" placeholder="Min. 8 characters" class="w-full pl-3 pr-10 py-2 text-sm border border-slate-200 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 bg-white shadow-sm placeholder-slate-300">
                        <button type="button" onclick="togglePasswordVisibility('create-password', 'create-eye-icon')" class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-400 hover:text-emerald-600 transition-colors">
                            <svg id="create-eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    @error('password') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div class="pt-2">
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1.5">Confirm Password *</label>
                    <div class="relative">
                        <input type="password" id="confirm-password" name="password_confirmation" required minlength="8" autocomplete="new-password" placeholder="Repeat password" class="w-full pl-3 pr-10 py-2 text-sm border border-slate-200 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 bg-white shadow-sm placeholder-slate-300">
                        <button type="button" onclick="togglePasswordVisibility('confirm-password', 'confirm-eye-icon')" class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-400 hover:text-emerald-600 transition-colors">
                            <svg id="confirm-eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <div>
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-5 border-b border-slate-200 pb-2">Operational Assignment</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1.5">System Role *</label>
                    <select name="role" required class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-1 focus:ring-emerald-500 bg-white shadow-sm">
                        <option value="">-- Select a Role --</option>
                        @foreach(\App\Enums\UserRole::cases() as $role)
                            <option value="{{ $role->value }}" {{ old('role') == $role->value ? 'selected' : '' }}>
                                {{ $role->label() }}
                            </option>
                        @endforeach
                    </select>
                    @error('role') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1.5">Department</label>
                    <select name="department_id" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-1 focus:ring-emerald-500 bg-white shadow-sm">
                        <option value="">-- Unassigned --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->dept_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1.5">Shift Start</label>
                    <input type="time" name="shift_start" value="{{ old('shift_start') }}" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 bg-white shadow-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1.5">Shift End</label>
                    <input type="time" name="shift_end" value="{{ old('shift_end') }}" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 bg-white shadow-sm">
                </div>
            </div>
        </div>

        <div class="pt-4 flex items-center gap-4">
            <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors shadow-sm">
                Create User
            </button>
            <a href="{{ route('admin.userManagement') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-800 transition-colors">
                Cancel
            </a>
        </div>
        
    </form>
</div>

@endsection