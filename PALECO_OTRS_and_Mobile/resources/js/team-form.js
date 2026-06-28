// resources/js/team-form.js

document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('members-container');
    
    // Safety check: ensure we are on the page with the team form
    if (!container) return;

    const updateMemberLockouts = () => {
        // Find all User dropdowns specifically
        const userSelects = document.querySelectorAll('select[name*="[user_id]"]');

        // 1. Collect all currently selected User IDs (excluding empty/null values)
        const takenUserIds = Array.from(userSelects)
            .map(select => select.value)
            .filter(id => id !== "");

        // 2. Apply Member Lockout
        userSelects.forEach(select => {
            const currentValue = select.value;
            
            // Loop through all options in this specific dropdown
            Array.from(select.options).forEach(option => {
                // If the option is an empty placeholder, always leave it enabled
                if (option.value === "") return;

                // Disable if the user is taken by another row, 
                // UNLESS it is the one currently selected in this row
                option.disabled = takenUserIds.includes(option.value) && option.value !== currentValue;
            });
        });
    };

    // Event Delegation: Only trigger logic when a User dropdown changes
    container.addEventListener('change', (e) => {
        if (e.target.matches('select[name*="[user_id]"]')) {
            updateMemberLockouts();
        }
    });

    // Run once on load to handle old() input values or existing teams
    updateMemberLockouts();
});