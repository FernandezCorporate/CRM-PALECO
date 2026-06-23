window.switchView = function(viewType) {
    const cardContainer = document.getElementById('card-view-container');
    const tableContainer = document.getElementById('table-view-container');
    const btnCard = document.getElementById('btn-card-view');
    const btnTable = document.getElementById('btn-table-view');

    // Safety check: only run if the containers actually exist on the page
    if (!cardContainer || !tableContainer) return;

    const activeClass = ['bg-white', 'text-slate-800', 'shadow-sm'];
    const inactiveClass = ['text-slate-500', 'hover:text-slate-700'];

    if (viewType === 'table') {
        cardContainer.classList.add('hidden');
        tableContainer.classList.remove('hidden');
        
        btnTable.classList.add(...activeClass);
        btnTable.classList.remove(...inactiveClass);
        btnCard.classList.add(...inactiveClass);
        btnCard.classList.remove(...activeClass);
    } else {
        tableContainer.classList.add('hidden');
        cardContainer.classList.remove('hidden');
        
        btnCard.classList.add(...activeClass);
        btnCard.classList.remove(...inactiveClass);
        btnTable.classList.add(...inactiveClass);
        btnTable.classList.remove(...activeClass);
    }

    // Save preference so it persists when clicking "Next Page"
    localStorage.setItem('departmentViewPreference', viewType);
};

// Apply saved preference automatically on page load
document.addEventListener('DOMContentLoaded', () => {
    // Only attempt to switch the view if the toggle buttons exist on the current page
    if (document.getElementById('btn-card-view')) {
        const savedView = localStorage.getItem('departmentViewPreference') || 'card';
        window.switchView(savedView);
    }
});