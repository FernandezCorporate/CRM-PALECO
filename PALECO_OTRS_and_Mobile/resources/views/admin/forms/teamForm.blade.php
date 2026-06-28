@extends('admin.base.sidebar')
@section('title', 'New Team')

@section('content')

@include('admin.prompts.admin-prompt')

<div class="max-w-4xl text-slate-800 pb-12">
    
    <div class="mb-8">
        <a href="{{ route('admin.teamManagement') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-emerald-600 transition-colors mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Teams
        </a>
        <h1 class="text-2xl font-bold text-slate-900 m-0">Add New Team</h1>
        <p class="text-sm text-slate-500 mt-1">Create a new field team and assign their operating shift and personnel.</p>
    </div>

    <form action="{{ route('admin.addTeam') }}" method="POST" class="space-y-8" autocomplete="off" id="team-form">
        @csrf
        
        <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-5 border-b border-slate-100 pb-3">Team Details</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1.5">Team Name *</label>
                    <input type="text" name="team_name" value="{{ old('team_name') }}" required maxlength="255" placeholder="e.g. Alpha Squad" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                    @error('team_name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1.5">Parent Department *</label>
                    <select name="department_id" required class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 outline-none bg-white">
                        <option value="">-- Select Department --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->dept_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1.5">Shift Start Time</label>
                    <input type="time" name="shift_start" value="{{ old('shift_start') }}" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-1 focus:ring-emerald-500 outline-none">
                    @error('shift_start') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1.5">Shift End Time</label>
                    <input type="time" name="shift_end" value="{{ old('shift_end') }}" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-1 focus:ring-emerald-500 outline-none">
                    @error('shift_end') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
            <div class="flex justify-between items-center mb-5 border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Team Members</h3>
                <button type="button" onclick="window.addMemberRow()" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 flex items-center gap-1 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Add Personnel
                </button>
            </div>
            
            <div id="members-container" class="space-y-3">
                @php $oldMembers = old('members', []); @endphp
                @foreach($oldMembers as $index => $member)
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 member-row bg-slate-50 p-3 rounded-lg border border-slate-100 items-center">
                        <div class="md:col-span-7">
                            <select name="members[{{ $index }}][user_id]" required class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg bg-white">
                                <option value="">-- Select Personnel --</option>
                                @foreach($personnels as $person)
                                    <option value="{{ $person->id }}" {{ $member['user_id'] == $person->id ? 'selected' : '' }}>
                                        {{ ucwords($person->first_name . ' ' . $person->last_name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-4">
                            <select name="members[{{ $index }}][role]" required class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg bg-white">
                                @foreach($memberRoles as $roleOption)
                                    <option value="{{ $roleOption->value }}" {{ (isset($member['role']) && $member['role'] == $roleOption->value) ? 'selected' : '' }}>
                                        {{ ucfirst($roleOption->value) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-1 text-right">
                            <button type="button" onclick="window.removeMemberRow(this)" class="text-rose-400 hover:text-rose-600 p-2 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <p class="text-xs text-slate-500 mt-4 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                You can assign members later via Team Management if you skip this step.
            </p>
        </div>

        <div class="pt-2 flex items-center gap-4">
            <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors shadow-sm">
                Create Team
            </button>
            <a href="{{ route('admin.teamManagement') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-800 transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>

<div id="member-template" class="hidden">
    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 member-row bg-slate-50 p-3 rounded-lg border border-slate-100 items-center">
        <div class="md:col-span-7">
            <select name="members[__INDEX__][user_id]" required class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg bg-white">
                <option value="">-- Select Personnel --</option>
                @foreach($personnels as $person)
                    <option value="{{ $person->id }}">{{ ucwords($person->first_name . ' ' . $person->last_name) }}</option>
                @endforeach
            </select>
        </div>
        <div class="md:col-span-4">
            <select name="members[__INDEX__][role]" required class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg bg-white">
                @foreach($memberRoles as $roleOption)
                    <option value="{{ $roleOption->value }}">
                        {{ ucfirst($roleOption->value) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="md:col-span-1 text-right">
            <button type="button" onclick="window.removeMemberRow(this)" class="text-rose-400 hover:text-rose-600 p-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
        </div>
    </div>
</div>
@endsection