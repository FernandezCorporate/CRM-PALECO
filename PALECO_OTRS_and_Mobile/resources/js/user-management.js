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