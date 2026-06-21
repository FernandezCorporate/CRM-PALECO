<div id="dept-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg mx-4 overflow-hidden">
        
        <form id="dept-form" method="POST">
            @csrf
            <div id="dept-method-container"></div>
            
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <h3 id="dept-modal-title" class="font-bold text-slate-800">Add New Department</h3>
                <button type="button" onclick="closeDeptModal()" class="text-slate-400 hover:text-slate-600 text-2xl">&times;</button>
            </div>
            
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-2">Department Name *</label>
                    <input type="text" id="dept_name_input" name="dept_name" required class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-2">Description</label>
                    <textarea id="dept_desc_input" name="dept_description" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg" rows="3"></textarea>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                <button type="button" onclick="closeDeptModal()" class="px-4 py-2 text-sm font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-100">Cancel</button>
                <button type="submit" id="dept-submit-btn" class="px-4 py-2 text-sm font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700">Create Department</button>
            </div>
        </form>
    </div>
</div>