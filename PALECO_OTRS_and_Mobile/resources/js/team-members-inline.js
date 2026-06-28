// resources/js/team-management.js

// Initialize the index based on how many rows currently exist on page load
let memberIndex = document.querySelectorAll('.member-row').length;

// Attach to 'window' so the HTML onclick="" attributes can find it!
window.addMemberRow = function() {
    const container = document.getElementById('members-container');
    const template = document.getElementById('member-template').innerHTML;
    
    // Replace __INDEX__ with a unique number so Laravel groups the array correctly
    const newRowHTML = template.replace(/__INDEX__/g, memberIndex);
    
    // Convert HTML string to DOM nodes safely
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = newRowHTML.trim();
    const newRow = tempDiv.firstChild;

    container.appendChild(newRow);
    memberIndex++;
};

// Attach to 'window' so the HTML onclick="" attributes can find it!
window.removeMemberRow = function(buttonElement) {
    const row = buttonElement.closest('.member-row');
    if (row) row.remove();
};