// public/js/team-modal.js

// Helper to format time (e.g., "08:00:00" to "8:00 AM")
function formatTime(timeString) {
    if (!timeString) return 'Not set';
    const timeWithoutSeconds = timeString.length > 5 ? timeString.slice(0, 5) : timeString;
    const [hourString, minute] = timeWithoutSeconds.split(':');
    const hour = parseInt(hourString, 10);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const formattedHour = hour % 12 || 12; 
    return `${formattedHour}:${minute} ${ampm}`;
}

// Main fetch function attached to the global window object
window.openTeamViewModal = async function(departmentId) {
    const modal = document.getElementById('team-view-modal');
    const title = document.getElementById('tvm-department-name');
    const listContainer = document.getElementById('tvm-team-list');

    // 1. Reset Modal State to Loading
    modal.classList.remove('hidden');
    title.textContent = 'Loading Teams...';
    listContainer.innerHTML = `
        <div class="flex flex-col justify-center items-center py-12 text-slate-400">
            <svg class="animate-spin h-8 w-8 text-emerald-500 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            <span class="text-sm font-semibold">Fetching data...</span>
        </div>
    `;

    try {
        // 2. Fetch from your backend route
        const response = await fetch(`/admin/departments/${departmentId}/teams`);
        if (!response.ok) throw new Error('Network response was not ok');
        const data = await response.json();

        // 3. Inject Data
        if (data.status === 'success') {
            title.textContent = `Teams under ${data.department_name} (${data.teams.length})`;

            // Empty State
            if (data.teams.length === 0) {
                listContainer.innerHTML = `
                    <div class="text-center py-12 bg-white rounded-lg border border-slate-200 shadow-sm">
                        <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <p class="text-slate-500 font-medium">No teams are currently assigned to this department.</p>
                    </div>
                `;
                return;
            }

            // Map over the JSON array to create HTML strings
            const teamsHtml = data.teams.map(team => {
                const shiftText = (team.shift_start && team.shift_end) 
                    ? `${formatTime(team.shift_start)} – ${formatTime(team.shift_end)}` 
                    : 'Flexible / Unassigned';
                
                const statusBadge = team.is_active 
                    ? `<span class="bg-emerald-100 text-emerald-700 text-[11px] font-bold px-2.5 py-1 rounded-full shrink-0">Active</span>`
                    : `<span class="bg-slate-200 text-slate-600 text-[11px] font-bold px-2.5 py-1 rounded-full shrink-0">Inactive</span>`;

                return `
                    <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm flex justify-between items-start">
                        <div>
                            <h4 class="text-base font-bold text-slate-900 mb-1">${team.team_name}</h4>
                            <div class="flex items-center gap-2 text-xs text-slate-500">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>Shift: ${shiftText}</span>
                            </div>
                        </div>
                        ${statusBadge}
                    </div>
                `;
            }).join(''); 

            listContainer.innerHTML = teamsHtml;
        }

    } catch (error) {
        console.error('Fetch error:', error);
        title.textContent = 'Connection Error';
        listContainer.innerHTML = `
            <div class="p-4 bg-rose-50 text-rose-600 rounded-lg border border-rose-200 font-medium text-sm text-center">
                Could not load teams at this time. Please try again later.
            </div>
        `;
    }
};

window.closeTeamViewModal = function() {
    document.getElementById('team-view-modal').classList.add('hidden');
};