<div id="user-modal" data-auth-id="{{ auth()->id() }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm transition-opacity duration-200 font-sans">
    
    <div class="bg-white rounded-xl shadow-xl w-full max-w-xl mx-4 overflow-hidden transform transition-all flex flex-col max-h-[90vh]">
        
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-white sticky top-0 z-10">
            <div>
                <h3 id="modal-title" class="text-base font-bold text-slate-800">Create New User</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">Fields marked with an asterisk (<span class="text-rose-500">*</span>) are required.</p>
            </div>
            <button type="button" onclick="closeUserModal()" class="text-slate-400 hover:text-slate-600 font-medium text-2xl transition-colors leading-none pb-2">&times;</button>
        </div>

        <form id="user-form" action="{{ route('admin.addUser') }}" method="POST" class="flex flex-col flex-1 overflow-y-auto m-0">
            @csrf
            
            <div id="method-container"></div>
            
            <div class="p-6 space-y-5 flex-1">
                
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Full Name</label>
                    <div class="grid grid-cols-12 gap-2">
                        <div class="col-span-12 sm:col-span-4">
                            <input type="text" id="user_first_name" name="first_name" required placeholder="First Name *" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-slate-800 placeholder-slate-400">
                        </div>
                        <div class="col-span-12 sm:col-span-3">
                            <input type="text" id="user_middle_name" name="middle_name" placeholder="Middle Name" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-slate-800 placeholder-slate-400">
                        </div>
                        <div class="col-span-12 sm:col-span-3">
                            <input type="text" id="user_last_name" name="last_name" required placeholder="Last Name *" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-slate-800 placeholder-slate-400">
                        </div>
                        <div class="col-span-12 sm:col-span-2">
                            <input type="text" id="user_name_ext" name="name_ext" placeholder="Ext (Jr.)" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-slate-800 placeholder-slate-400">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Username <span class="text-rose-500">*</span></label>
                        <input type="text" id="user_username" name="username" required placeholder="Enter login username" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-slate-800 placeholder-slate-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Email Address <span class="text-rose-500">*</span></label>
                        <input type="email" id="user_email" name="email" required placeholder="name@company.com" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-slate-800 placeholder-slate-400">
                    </div>
                </div>

                <div class="bg-slate-50 p-4 rounded-lg border border-slate-100">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-baseline mb-3 gap-1">
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider m-0">Security</label>
                        <span id="password-hint" class="text-[11px] text-slate-500">Minimum 8 characters. Both fields required.</span>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="relative w-full">
                            <input type="password" id="password-field" name="password" required placeholder="Password *" minlength="8" class="w-full pl-3 pr-12 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-slate-800 placeholder-slate-400">
                            <button type="button" onclick="togglePasswordVisibility('password-field', 'password-toggle-btn')" id="password-toggle-btn" class="absolute right-2 top-1/2 -translate-y-1/2 bg-transparent border-none text-[10px] font-bold text-slate-400 hover:text-slate-600 cursor-pointer p-1 uppercase tracking-wider select-none">Show</button>
                        </div>
                        <div class="relative w-full">
                            <input type="password" id="password-confirm-field" name="password_confirmation" required placeholder="Confirm Password *" minlength="8" class="w-full pl-3 pr-12 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-slate-800 placeholder-slate-400">
                            <button type="button" onclick="togglePasswordVisibility('password-confirm-field', 'password-confirm-toggle-btn')" id="password-confirm-toggle-btn" class="absolute right-2 top-1/2 -translate-y-1/2 bg-transparent border-none text-[10px] font-bold text-slate-400 hover:text-slate-600 cursor-pointer p-1 uppercase tracking-wider select-none">Show</button>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                    <div class="col-span-12 sm:col-span-6">
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Department</label>
                        <select id="user_department" name="department_id" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-slate-800 bg-white appearance-none cursor-pointer">
                            <option value="">-- Select Department --</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->dept_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-span-12 sm:col-span-3">
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Shift Start</label>
                        <input type="time" id="user_shift_start" name="shift_start" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-slate-800">
                    </div>
                    
                    <div class="col-span-12 sm:col-span-3">
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Shift End</label>
                        <input type="time" id="user_shift_end" name="shift_end" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-slate-800">
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-baseline mb-2">
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Role Assignment <span class="text-rose-500">*</span></label>
                        <span id="role-warning" class="hidden text-[10px] text-rose-500 font-semibold uppercase tracking-wider">Cannot change own role</span>
                    </div>
                    
                    <select id="user_role" name="role" required class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-slate-800 bg-white appearance-none cursor-pointer disabled:bg-slate-100 disabled:text-slate-400 disabled:cursor-not-allowed">
                        <option value="">-- Select Role --</option>
                        @foreach (\App\Enums\UserRole::cases() as $role)
                            <option value="{{ $role->value }}">{{ ucwords(str_replace('_', ' ', $role->value)) }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3 sticky bottom-0 z-10">
                <button type="button" onclick="closeUserModal()" class="px-4 py-2 text-sm font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-100 transition-colors shadow-sm">
                    Cancel
                </button>
                <button type="submit" id="submit-btn" class="px-4 py-2 text-sm font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 shadow-sm transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Create User
                </button>
            </div>
        </form>

    </div>
</div>