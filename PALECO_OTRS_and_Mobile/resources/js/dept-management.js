window.openDeptModal = function(department = null) {
    const modal = document.getElementById('dept-modal');
    const form = document.getElementById('dept-form');
    const methodContainer = document.getElementById('dept-method-container');
    const modalTitle = document.getElementById('dept-modal-title');
    const submitBtn = document.getElementById('dept-submit-btn');

    // Always clear the form first to avoid ghost data
    form.reset();

    if (department) {
        // --- EDIT MODE ---
        modalTitle.textContent = 'Edit Department';
        submitBtn.textContent = 'Update Department';
        
        // Point the form to the Update route URL
        form.action = `/admin/departments/${department.id}`;
        // Inject the PUT method spoof
        methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';

        // Populate fields
        document.getElementById('dept_name_input').value = department.dept_name || '';
        document.getElementById('dept_desc_input').value = department.dept_description || '';
    } else {
        // --- CREATE MODE ---
        modalTitle.textContent = 'Add New Department';
        submitBtn.textContent = 'Create Department';
        
        // Point the form to the Create route URL
        form.action = `/admin/departments`;
        // Clear the method container (defaults to POST)
        methodContainer.innerHTML = '';
    }

    // Show the modal
    modal.classList.remove('hidden');
};

window.closeDeptModal = function() {
    const modal = document.getElementById('dept-modal');
    const form = document.getElementById('dept-form');
    
    modal.classList.add('hidden');
    form.reset();
};