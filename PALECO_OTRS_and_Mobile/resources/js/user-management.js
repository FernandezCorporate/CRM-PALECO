// resources/js/user-management.js

window.openUserModal = function(user = null) {
    const modal = document.getElementById('user-modal');
    const authId = parseInt(modal.getAttribute('data-auth-id')); // 💡 Grab the logged-in user's ID
    const form = document.getElementById('user-form');
    const methodContainer = document.getElementById('method-container');
    const modalTitle = document.getElementById('modal-title');
    const submitBtn = document.getElementById('submit-btn');

    const securitySection = document.getElementById('security-section');
    const passField = document.getElementById('password-field');
    const passConfirm = document.getElementById('password-confirm-field');
    
    // 💡 Elements needed for the role logic
    const roleSelect = document.getElementById('user_role');
    const roleWarning = document.getElementById('role-warning');

    // Reset all inputs before populating
    form.reset();

    if (user) {
        // --- EDIT MODE ---
        modalTitle.textContent = 'Edit User: ' + user.username;
        submitBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Update User';
        
        form.action = `/admin/users/${user.id}`;
        methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';

        securitySection.classList.add('hidden');
        passField.disabled = true;
        passConfirm.disabled = true;
        passField.required = false;
        passConfirm.required = false;

        document.getElementById('user_first_name').value = user.first_name || '';
        document.getElementById('user_middle_name').value = user.middle_name || '';
        document.getElementById('user_last_name').value = user.last_name || '';
        document.getElementById('user_name_ext').value = user.name_ext || '';
        document.getElementById('user_username').value = user.username || '';
        document.getElementById('user_email').value = user.email || '';
        document.getElementById('user_contact').value = user.contact || '';
        
        const roleValue = typeof user.role === 'object' ? user.role.value : user.role;
        roleSelect.value = roleValue || '';

        // 💡 UX Logic: Disable role selection if the admin is editing their own account
        if (user.id === authId) {
            roleSelect.disabled = true;
            roleSelect.required = false; // Remove HTML requirement since it's disabled
            roleWarning.classList.remove('hidden');
        } else {
            roleSelect.disabled = false;
            roleSelect.required = true;
            roleWarning.classList.add('hidden');
        }

        document.getElementById('user_department').value = user.department_id || '';
        document.getElementById('user_shift_start').value = user.shift_start || '';
        document.getElementById('user_shift_end').value = user.shift_end || '';

    } else {
        // --- CREATE MODE ---
        modalTitle.textContent = 'Create New User';
        submitBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Create User';
        
        form.action = `/admin/users`;
        methodContainer.innerHTML = '';

    securitySection.classList.remove('hidden');
        passField.disabled = false;
        passConfirm.disabled = false;
        passField.required = true;
        passConfirm.required = true;
        
        // 💡 Ensure dropdown is active for new users
        roleSelect.disabled = false;
        roleSelect.required = true;
        roleWarning.classList.add('hidden');
    }

    modal.classList.remove('hidden');
};

window.closeUserModal = function() {
    const modal = document.getElementById('user-modal');
    const form = document.getElementById('user-form');
    
    // Reset passwords to text type safely just in case they were toggled
    const passInput = document.getElementById('password-field');
    const passConfirm = document.getElementById('password-confirm-field');
    if(passInput.type === 'text') togglePasswordVisibility('password-field', 'password-toggle-btn');
    if(passConfirm.type === 'text') togglePasswordVisibility('password-confirm-field', 'password-confirm-toggle-btn');
    
    form.reset();
    modal.classList.add('hidden');
};

window.togglePasswordVisibility = function(fieldId, buttonId) {
    const passwordInput = document.getElementById(fieldId);
    const toggleButton = document.getElementById(buttonId);
    
    if (passwordInput && toggleButton) {
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleButton.textContent = "Hide";
            toggleButton.classList.remove('text-slate-400');
            toggleButton.classList.add('text-emerald-600');
        } else {
            passwordInput.type = "password";
            toggleButton.textContent = "Show";
            toggleButton.classList.remove('text-emerald-600');
            toggleButton.classList.add('text-slate-400');
        }
    }
};

window.openViewModal = function(user) {
    if (!user) return;

    // Construct Full Name
    const firstName = user.first_name || '';
    const lastName = user.last_name || '';
    const fullName = `${firstName} ${user.middle_name ? user.middle_name.charAt(0) + '. ' : ''}${lastName} ${user.name_ext || ''}`.trim();
    
    // Set Avatar Initials
    const initials = (firstName.charAt(0) + lastName.charAt(0)).toUpperCase() || '--';
    document.getElementById('view_avatar_initials').textContent = initials;
    
    // Populate Text Fields
    document.getElementById('view_full_name').textContent = fullName.replace(/\b\w/g, l => l.toUpperCase());
    document.getElementById('view_username').textContent = user.username || '--';
    document.getElementById('view_email').textContent = user.email || 'No email provided';
    document.getElementById('view_contact').textContent = user.contact || '--';
    
    // Format Role
    const roleValue = typeof user.role === 'object' ? user.role.value : user.role;
    document.getElementById('view_role_badge').textContent = roleValue ? roleValue.replace('_', ' ') : 'Unassigned';

    // Department & Shifts
    document.getElementById('view_department').textContent = user.department ? user.department.dept_name : 'Unassigned';
    document.getElementById('view_shift_start').textContent = formatTime(user.shift_start) || '--';
    document.getElementById('view_shift_end').textContent = formatTime(user.shift_end) || '--';

    // Last Login formatting (Handling standard MySQL Datetime string)
    if (user.last_login_at) {
        const date = new Date(user.last_login_at);
        document.getElementById('view_last_login').textContent = date.toLocaleString('en-PH', { 
            month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true 
        });
    } else {
        document.getElementById('view_last_login').textContent = 'Never logged in';
        document.getElementById('view_last_login').classList.add('italic', 'text-slate-400');
    }

    // Status Badge Logic
    const statusBadge = document.getElementById('view_status_badge');
    if (user.is_active) {
        statusBadge.textContent = 'Active';
        statusBadge.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide bg-emerald-100 text-emerald-700';
    } else {
        statusBadge.textContent = 'Inactive';
        statusBadge.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide bg-rose-100 text-rose-700';
    }

    // Show Modal
    document.getElementById('view-modal').classList.remove('hidden');
};

window.closeViewModal = function() {
    document.getElementById('view-modal').classList.add('hidden');
};

// Helper function to convert 24hr "14:30" to "2:30 PM"
function formatTime(timeString) {
    if (!timeString) return null;
    const [hourString, minute] = timeString.split(':');
    const hour = +hourString % 24;
    return (hour % 12 || 12) + ':' + minute + (hour < 12 ? ' AM' : ' PM');
}