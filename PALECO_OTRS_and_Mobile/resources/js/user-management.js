// resources/js/user-management.js

window.openUserModal = function(user = null) {
    const modal = document.getElementById('user-modal');
    const form = document.getElementById('user-form');
    const methodContainer = document.getElementById('method-container');
    const modalTitle = document.getElementById('modal-title');
    const submitBtn = document.getElementById('submit-btn');
    const passHint = document.getElementById('password-hint');
    const passField = document.getElementById('password-field');
    const passConfirm = document.getElementById('password-confirm-field');

    form.reset();

    if (user) {
        modalTitle.textContent = 'Edit User: ' + user.username;
        submitBtn.innerHTML = 'Update User'; // Simplified for brevity
        form.action = `/admin/users/${user.id}`;
        methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';
        passHint.textContent = 'Leave blank to keep current password.';
        passField.required = false;
        passConfirm.required = false;
        
        document.getElementById('user_first_name').value = user.first_name || '';
        document.getElementById('user_username').value = user.username || '';
        // ... (populate remaining fields)
    } else {
        modalTitle.textContent = 'Create New User';
        form.action = `/admin/users`;
        methodContainer.innerHTML = '';
        passField.required = true;
    }
    modal.classList.remove('hidden');
};

window.closeUserModal = function() {
    document.getElementById('user-modal').classList.add('hidden');
};

window.togglePasswordVisibility = function(fieldId, buttonId) {
    const input = document.getElementById(fieldId);
    const btn = document.getElementById(buttonId);
    input.type = input.type === 'password' ? 'text' : 'password';
    btn.textContent = input.type === 'password' ? 'Show' : 'Hide';
};