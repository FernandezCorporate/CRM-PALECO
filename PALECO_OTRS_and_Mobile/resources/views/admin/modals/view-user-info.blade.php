<div id="view-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm transition-opacity duration-200 font-sans">
    
    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl mx-4 overflow-hidden transform transition-all flex flex-col">
        
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-white">
            <h3 class="text-base font-bold text-slate-800">User Profile Details</h3>
            <button type="button" onclick="closeViewModal()" class="text-slate-400 hover:text-slate-600 font-medium text-2xl transition-colors leading-none pb-2">&times;</button>
        </div>

        <div class="p-6 overflow-y-auto max-h-[80vh]">
            
            <div class="flex items-center gap-4 mb-8">
                <div class="h-16 w-16 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 text-2xl font-bold border-2 border-emerald-200" id="view_avatar_initials">
                    --
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-800" id="view_full_name">User Name</h2>
                    <div class="flex items-center gap-2 mt-1">
                        <span id="view_role_badge" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide bg-slate-100 text-slate-600">Role</span>
                        <span id="view_status_badge" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide">Status</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">Account Information</h4>
                    
                    <div>
                        <p class="text-[11px] text-slate-500 uppercase font-semibold">Username</p>
                        <p class="text-sm font-medium text-slate-800" id="view_username">--</p>
                    </div>
                    
                    <div>
                        <p class="text-[11px] text-slate-500 uppercase font-semibold">Email Address</p>
                        <p class="text-sm font-medium text-slate-800" id="view_email">--</p>
                    </div>

                    <div>
                        <p class="text-[11px] text-slate-500 uppercase font-semibold">Contact Number</p>
                        <p class="text-sm font-medium text-slate-800" id="view_contact">--</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">Operational Details</h4>
                    
                    <div>
                        <p class="text-[11px] text-slate-500 uppercase font-semibold">Department</p>
                        <p class="text-sm font-medium text-slate-800" id="view_department">--</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-[11px] text-slate-500 uppercase font-semibold">Shift Start</p>
                            <p class="text-sm font-medium text-slate-800" id="view_shift_start">--</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-slate-500 uppercase font-semibold">Shift End</p>
                            <p class="text-sm font-medium text-slate-800" id="view_shift_end">--</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-[11px] text-slate-500 uppercase font-semibold">Last Login</p>
                        <p class="text-sm font-medium text-slate-800" id="view_last_login">--</p>
                    </div>
                </div>

            </div>
        </div>

        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end">
            <button type="button" onclick="closeViewModal()" class="px-4 py-2 text-sm font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-100 transition-colors shadow-sm">
                Close
            </button>
        </div>

    </div>
</div>