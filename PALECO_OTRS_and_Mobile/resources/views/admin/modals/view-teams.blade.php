<div id="team-view-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm transition-opacity duration-200">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-3xl mx-4 overflow-hidden flex flex-col max-h-[85vh]">
        
        <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-start bg-white sticky top-0 z-10">
            <div>
                <h2 id="tvm-department-name" class="text-xl font-bold text-slate-900">Loading Teams...</h2>
                <p class="text-sm text-slate-500 mt-1">Viewing all active and inactive teams.</p>
            </div>
            <button type="button" onclick="closeTeamViewModal()" class="text-slate-400 hover:text-slate-600 font-medium text-2xl transition-colors leading-none">&times;</button>
        </div>

        <div id="tvm-team-list" class="p-6 overflow-y-auto flex-1 bg-slate-50 space-y-4">
            <div class="flex justify-center items-center py-12 text-slate-400">
                <svg class="animate-spin h-8 w-8 text-emerald-500 mb-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </div>
        </div>

    </div>
</div>