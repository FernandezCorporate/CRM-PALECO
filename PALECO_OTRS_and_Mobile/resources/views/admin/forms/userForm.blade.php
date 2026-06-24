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
                        @foreach(\App\Enums\UserRole::cases() as $role)
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

            <label class="block text-xs font-bold text-slate-600 uppercase mb-3">Shift Schedule</label>
            <div id="shift-error" class="hidden px-4 py-3 bg-rose-50 border border-rose-200 text-rose-600 rounded-lg text-sm mb-4"></div>
            
            <div id="shift-container" class="space-y-3 mb-4">
                @php
                    $shiftData = [];
                    if (old('shifts')) {
                        $shiftData = old('shifts');
                    } elseif (isset($user) && $user->shifts->isNotEmpty()) {
                        foreach($user->shifts as $s) {
                            $shiftData[] = [
                                'day_of_week' => $s->day_of_week->value,
                                'start_time' => \Carbon\Carbon::parse($s->start_time)->format('H:i'),
                                'end_time' => \Carbon\Carbon::parse($s->end_time)->format('H:i')
                            ];
                        }
                    }
                @endphp

                @foreach($shiftData as $index => $shift)
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 shift-row bg-slate-50 p-4 rounded-lg border border-slate-100 items-center transition-colors">
                        <div class="md:col-span-2">
                            <select name="shifts[{{$index}}][day_of_week]" class="shift-day w-full px-3 py-2 text-sm border border-slate-200 rounded-lg">
                                @foreach(\App\Enums\DayOfWeek::cases() as $day)
                                    <option value="{{ $day->value }}" {{ ($shift['day_of_week'] ?? '') == $day->value ? 'selected' : '' }}>{{ $day->value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="time" name="shifts[{{$index}}][start_time]" value="{{ $shift['start_time'] ?? '' }}" required class="shift-start w-full px-3 py-2 text-sm border border-slate-200 rounded-lg">
                        <div class="flex gap-2">
                            <input type="time" name="shifts[{{$index}}][end_time]" value="{{ $shift['end_time'] ?? '' }}" required class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg">
                            <button type="button" onclick="removeShiftRow(this)" class="text-rose-500 hover:text-rose-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                        @error("shifts.{$index}.start_time") <div class="md:col-span-4 text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                        @error("shifts.{$index}.end_time") <div class="md:col-span-4 text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                    </div>
                @endforeach
            </div>

            <button type="button" onclick="addShiftRow()" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Add Another Shift
            </button>
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

<div id="shift-template" class="hidden">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 shift-row bg-slate-50 p-4 rounded-lg border border-slate-100 items-center transition-colors">
        <div class="md:col-span-2">
            <select name="shifts[__INDEX__][day_of_week]" class="shift-day w-full px-3 py-2 text-sm border border-slate-200 rounded-lg">
                @foreach(\App\Enums\DayOfWeek::cases() as $day)
                    <option value="{{ $day->value }}">{{ $day->value }}</option>
                @endforeach
            </select>
        </div>
        <input type="time" name="shifts[__INDEX__][start_time]" required class="shift-start w-full px-3 py-2 text-sm border border-slate-200 rounded-lg">
        <div class="flex gap-2">
            <input type="time" name="shifts[__INDEX__][end_time]" required class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg">
            <button type="button" onclick="removeShiftRow(this)" class="text-rose-500 hover:text-rose-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
        </div>
    </div>
</div>

@endsection