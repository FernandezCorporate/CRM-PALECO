@extends('admin.sidebar')
@section('title', 'User Details - PALECO CRM-CWD')

@section('content')

@include('admin.prompts.admin-prompt')

<div class="max-w-6xl mx-auto text-slate-800 pb-12">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <a href="{{ route('admin.userManagement') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-emerald-600 transition-colors mb-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to User List
            </a>
            <h1 class="text-2xl font-bold text-slate-900 m-0">User Profile</h1>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.updateUserForm', $user->id) }}" class="px-4 py-2 text-sm font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit Profile
            </a>

            <form action="{{ route('admin.toggleStatus', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to {{ $user->is_active ? 'deactivate' : 'activate' }} this account?');">
                @csrf
                @method('PATCH')
                @if($user->is_active)
                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-rose-700 bg-rose-50 border border-rose-200 rounded-lg hover:bg-rose-100 transition-colors flex items-center gap-2 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Deactivate Account
                    </button>
                @else
                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition-colors flex items-center gap-2 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Activate Account
                    </button>
                @endif
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-1 space-y-6">
            
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="h-24 bg-emerald-600"></div>
                <div class="px-6 pb-6">
                    
                    <div class="pt-6">
                        <h2 class="text-xl font-bold text-slate-900 leading-tight">
                            {{ ucwords($user->first_name) }} 
                            {{ $user->middle_name ? strtoupper(substr($user->middle_name, 0, 1)) . '.' : '' }} 
                            {{ ucwords($user->last_name) }} 
                            {{ $user->name_ext ? ucwords($user->name_ext) : '' }}
                        </h2>
                        <p class="text-sm text-slate-500 font-medium mt-1">Username: {{ $user->username }}</p>
                        
                        <div class="flex items-center gap-2 mt-4">
                            <span class="text-[11px] font-bold px-2.5 py-1 rounded-full {{ $user->role->badgeClasses() }}">
                                {{ $user->role->label() }}
                            </span>
                            @if($user->is_active)
                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-green-100 text-green-700 flex items-center gap-1 w-max">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Active
                                </span>
                            @else
                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-rose-100 text-rose-700 flex items-center gap-1 w-max">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Inactive
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Contact Information</h3>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-slate-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <div>
                            <p class="text-xs font-semibold text-slate-500">Email Address</p>
                            <p class="text-sm font-medium text-slate-800">{{ $user->email ?? 'Not provided' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-slate-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        <div>
                            <p class="text-xs font-semibold text-slate-500">Phone Number</p>
                            <p class="text-sm font-medium text-slate-800">{{ $user->contact }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-5">Operational Details</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-slate-50 p-4 rounded-lg border border-slate-100">
                        <p class="text-xs font-semibold text-slate-500 mb-1">Assigned Department</p>
                        @if($user->department)
                            <p class="text-base font-bold text-slate-800">{{ $user->department->dept_name }}</p>
                        @else
                            <p class="text-base font-bold text-slate-400 italic">Unassigned</p>
                        @endif
                    </div>

                    <div class="bg-slate-50 p-4 rounded-lg border border-slate-100">
                        <p class="text-xs font-semibold text-slate-500 mb-1">Assigned Shift</p>
                        @if($user->shift_start && $user->shift_end)
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-base font-bold text-slate-800">
                                    {{ \Carbon\Carbon::parse($user->shift_start)->format('g:i A') }} - {{ \Carbon\Carbon::parse($user->shift_end)->format('g:i A') }}
                                </p>
                            </div>
                        @else
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-base font-bold text-slate-400 italic">No shift assigned</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-5">System Information</h3>
                
                <ul class="divide-y divide-slate-100">
                    <li class="py-3 flex justify-between items-center">
                        <span class="text-sm text-slate-500">Account Created</span>
                        <span class="text-sm font-medium text-slate-800">{{ $user->created_at->format('M d, Y h:i A') }}</span>
                    </li>
                    <li class="py-3 flex justify-between items-center">
                        <span class="text-sm text-slate-500">Last Profile Update</span>
                        <span class="text-sm font-medium text-slate-800">{{ $user->updated_at->format('M d, Y h:i A') }}</span>
                    </li>
                    <li class="py-3 flex justify-between items-center">
                        <span class="text-sm text-slate-500">Last Login Date</span>
                        @if($user->last_login_at)
                            <span class="text-sm font-medium text-slate-800">{{ $user->last_login_at->format('M d, Y h:i A') }}</span>
                        @else
                            <span class="text-sm font-medium text-slate-400 italic">Never logged in</span>
                        @endif
                    </li>
                </ul>
            </div>

        </div>
    </div>
</div>

@endsection