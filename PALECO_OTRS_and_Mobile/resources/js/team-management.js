// Helper to format time safely (e.g., "08:00:00" to "8:00 AM")
function formatTime(timeVal) {
    if (!timeVal) return 'Not set';
    
    // Force the value to be a string just in case the database sent an integer
    const timeString = String(timeVal); 
    const timeWithoutSeconds = timeString.length > 5 ? timeString.slice(0, 5) : timeString;
    
    // Safety check: if there is no colon, it's not a standard time format, so return as-is
    if (!timeWithoutSeconds.includes(':')) return timeWithoutSeconds;

    const [hourString, minute] = timeWithoutSeconds.split(':');
    const hour = parseInt(hourString, 10);
    
    // Safety check: if hour isn't a valid number, abort formatting
    if (isNaN(hour)) return timeWithoutSeconds;

    const ampm = hour >= 12 ? 'PM' : 'AM';
    const formattedHour = hour % 12 || 12; 
    return `${formattedHour}:${minute} ${ampm}`;
}

window.openTeamViewModal = async function(departmentId) {
    const modal = document.getElementById('team-view-modal');
    const title = document.getElementById('tvm-department-name');
    const loading = document.getElementById('tvm-loading');
    const listTeams = document.getElementById('tvm-list-teams');
    const listForemen = document.getElementById('tvm-list-foremen');
    const tabsContainer = document.getElementById('tvm-tabs');

    // Reset State
    modal.classList.remove('hidden');
    tabsContainer.classList.add('hidden');
    loading.classList.remove('hidden');
    listTeams.classList.add('hidden');
    listForemen.classList.add('hidden');
    title.textContent = 'Loading...';

    try {
        const response = await fetch(`/admin/departments/${departmentId}/teams`);
        if (!response.ok) throw new Error('Network response was not ok');
        const data = await response.json();

        if (data.status === 'success') {
            title.textContent = data.department_name || 'Department Details';
            
            // Safely default to empty arrays if data is missing
            const teamsData = data.teams || [];
            const foremenData = data.foremen || [];

            document.getElementById('tvm-count-teams').textContent = teamsData.length;
            document.getElementById('tvm-count-foremen').textContent = foremenData.length;

            // 1. Render Teams Safely
            if (teamsData.length === 0) {
                listTeams.innerHTML = `<div class="text-center py-8 text-slate-500">No teams assigned.</div>`;
            } else {
                listTeams.innerHTML = teamsData.map(team => {
                    const shiftText = (team.shift_start && team.shift_end) 
                        ? `${formatTime(team.shift_start)} - ${formatTime(team.shift_end)}` 
                        : 'Flexible / Unassigned';
                    
                    const statusBadge = team.is_active 
                        ? `<span class="bg-emerald-100 text-emerald-700 text-[11px] font-bold px-2.5 py-1 rounded-full shrink-0">Active</span>`
                        : `<span class="bg-slate-200 text-slate-600 text-[11px] font-bold px-2.5 py-1 rounded-full shrink-0">Inactive</span>`;

                    return `
                        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm hover:shadow-md hover:border-emerald-200 transition-all flex justify-between items-center group">
                            <div>
                                <h4 class="text-base font-bold text-slate-900 mb-1">${team.team_name || 'Unnamed Team'}</h4>
                                <div class="text-xs text-slate-500 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Shift: ${shiftText}
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                ${statusBadge}
                                <button type="button" onclick="console.log('View Team Details Clicked for ID:', ${team.id})" class="text-slate-400 hover:text-emerald-600 transition-colors p-1" title="View Team Details">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>`;
                }).join('');
            }

            // 2. Render Foremen Safely
            if (foremenData.length === 0) {
                listForemen.innerHTML = `<div class="text-center py-8 text-slate-500">No foremen assigned.</div>`;
            } else {
                listForemen.innerHTML = foremenData.map(foreman => {
                    const fName = String(foreman.first_name || '');
                    const lName = String(foreman.last_name || '');
                    const contact = String(foreman.contact || 'No contact provided');
                    const email = String(foreman.email || 'No email provided');

                    const name = `${fName} ${lName}`.trim().replace(/\b\w/g, l => l.toUpperCase());

                    const initialF = fName ? fName.charAt(0).toUpperCase() : '';
                    const initialL = lName ? lName.charAt(0).toUpperCase() : '';
                    const initials = (initialF + initialL) || '--';

                    // 💡 Note: We stringify the foreman object so it can be passed safely into your existing openViewModal function
                    const foremanJson = JSON.stringify(foreman).replace(/"/g, '&quot;');

                    return `
                        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm hover:shadow-md hover:border-emerald-200 transition-all flex flex-col md:flex-row justify-between items-start md:items-center gap-4 group">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold border border-slate-200 shrink-0">
                                    ${initials}
                                </div>
                                <div>
                                    <h4 class="text-base font-bold text-slate-900">${name || 'Unnamed User'}</h4>
                                    <p class="text-xs text-slate-500">System Username: ${foreman.username || 'N/A'}</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between w-full md:w-auto gap-4">
                                <div class="text-xs text-slate-600 text-left md:text-right md:min-w-[120px]">
                                    <p class="font-medium">${contact}</p>
                                    <p class="text-slate-400">${email}</p>
                                </div>
                                
                                <button type="button" onclick="openViewModal(${foremanJson})" class="text-slate-400 hover:text-sky-600 transition-colors p-1" title="View Foreman Details">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>`;
                }).join('');
            }

            // Reveal UI and default to Teams tab
            loading.classList.add('hidden');
            tabsContainer.classList.remove('hidden');
            switchTvmTab('teams');
        }
    } catch (error) {
        console.error("Modal Render Error:", error);
        loading.innerHTML = `<div class="p-4 bg-rose-50 text-rose-600 rounded-lg border border-rose-200 font-medium text-sm text-center">Could not load data. Check console for details.</div>`;
    }
};

window.switchTvmTab = function(tabName) {
    const listTeams = document.getElementById('tvm-list-teams');
    const listForemen = document.getElementById('tvm-list-foremen');
    const btnTeams = document.getElementById('tab-btn-teams');
    const btnForemen = document.getElementById('tab-btn-foremen');

    btnTeams.className = "pb-3 border-b-2 font-semibold text-sm transition-colors border-transparent text-slate-500 hover:text-slate-700";
    btnForemen.className = "pb-3 border-b-2 font-semibold text-sm transition-colors border-transparent text-slate-500 hover:text-slate-700";
    listTeams.classList.add('hidden');
    listForemen.classList.add('hidden');

    if (tabName === 'teams') {
        btnTeams.className = "pb-3 border-b-2 font-semibold text-sm transition-colors border-emerald-500 text-emerald-600";
        listTeams.classList.remove('hidden');
    } else {
        btnForemen.className = "pb-3 border-b-2 font-semibold text-sm transition-colors border-emerald-500 text-emerald-600";
        listForemen.classList.remove('hidden');
    }
};

window.closeTeamViewModal = function() {
    document.getElementById('team-view-modal').classList.add('hidden');
};