// resources/js/user-management.js

/**
 * Master Modal Controller handles both Create and Edit states
 * @param {Object|null} user - Pass user JSON to edit, pass nothing to create
 */
window.openUserModal = function(user = null) {
    const modal = document.getElementById('user-modal');
    const form = document.getElementById('user-form');
    const methodContainer = document.getElementById('method-container');
    const modalTitle = document.getElementById('modal-title');
    const submitBtn = document.getElementById('submit-btn');
    const passHint = document.getElementById('password-hint');
    const passField = document.getElementById('password-field');
    const passConfirm = document.getElementById('password-confirm-field');

    // Reset all inputs before populating
    form.reset();

    if (user) {
        // --- EDIT MODE ---
        modalTitle.textContent = 'Edit User: ' + user.username;
        submitBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Update User';
        
        // Change action to update route
        form.action = `/admin/users/${user.id}`;
        
        // Inject Laravel's form method spoofing for PUT
        methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';

        // Passwords are optional on edit
        passHint.textContent = 'Leave blank to keep current password.';
        passField.required = false;
        passField.placeholder = "New Password (Optional)";
        passConfirm.required = false;
        passConfirm.placeholder = "Confirm New Password";

        // Populate standard text fields
        document.getElementById('user_first_name').value = user.first_name || '';
        document.getElementById('user_middle_name').value = user.middle_name || '';
        document.getElementById('user_last_name').value = user.last_name || '';
        document.getElementById('user_name_ext').value = user.name_ext || '';
        document.getElementById('user_username').value = user.username || '';
        document.getElementById('user_email').value = user.email || '';
        
        // Safely map Enum value if necessary
        const roleValue = typeof user.role === 'object' ? user.role.value : user.role;
        document.getElementById('user_role').value = roleValue || '';

        // Populate new Department and Shift fields
        document.getElementById('user_department').value = user.department_id || '';
        document.getElementById('user_shift_start').value = user.shift_start || '';
        document.getElementById('user_shift_end').value = user.shift_end || '';

    } else {
        // --- CREATE MODE ---
        modalTitle.textContent = 'Create New User';
        submitBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Create User';
        
        // Revert action to standard POST route
        form.action = `/admin/users`;
        
        // Remove PUT override
        methodContainer.innerHTML = '';

        // Passwords are required on create
        passHint.textContent = 'Minimum 8 characters. Both fields required.';
        passField.required = true;
        passField.placeholder = "Password *";
        passConfirm.required = true;
        passConfirm.placeholder = "Confirm Password *";
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