let shiftIndex = document.querySelectorAll('.shift-row').length;

window.addShiftRow = function() {
    const container = document.getElementById('shift-container');
    const template = document.getElementById('shift-template').innerHTML;
    
    // Replace __INDEX__ with a unique number to ensure PHP groups the array correctly
    const newRowHTML = template.replace(/__INDEX__/g, shiftIndex);
    
    const parser = new DOMParser();
    const doc = parser.parseFromString(newRowHTML, 'text/html');
    const newRow = doc.body.firstChild;

    container.appendChild(newRow);
    shiftIndex++;
};

window.removeShiftRow = function(buttonElement) {
    const row = buttonElement.closest('.shift-row');
    if (row) row.remove();
};

// Real-time Form Validation on Submit (Continuous Minutes Algorithm)
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('user-form');
    const errorBlock = document.getElementById('shift-error');

    const dayMap = {
        'Monday': 0, 'Tuesday': 1, 'Wednesday': 2,
        'Thursday': 3, 'Friday': 4, 'Saturday': 5, 'Sunday': 6
    };

    if (form) {
        form.addEventListener('submit', function(e) {
            const rows = document.querySelectorAll('.shift-row');
            let intervals = [];
            let hasOverlap = false;

            // Reset visual errors
            rows.forEach(row => {
                row.classList.remove('border-rose-400', 'bg-rose-50');
            });

            rows.forEach(row => {
                const day = row.querySelector('.shift-day').value;
                const startTime = row.querySelector('.shift-start').value;
                const endInput = row.querySelector('input[name*="[end_time]"]');
                const endTime = endInput ? endInput.value : '';
                
                if (day && startTime && endTime) {
                    const dayIdx = dayMap[day];
                    let [sH, sM] = startTime.split(':').map(Number);
                    let [eH, eM] = endTime.split(':').map(Number);

                    let startMin = (dayIdx * 24 * 60) + (sH * 60) + sM;
                    let endMin = (dayIdx * 24 * 60) + (eH * 60) + eM;

                    // It crosses midnight
                    if (endMin <= startMin) {
                        endMin += (24 * 60);
                    }

                    // Handle Sunday crossing into Monday
                    let subIntervals = [];
                    if (endMin > 7 * 24 * 60) {
                        subIntervals.push({ row: row, start: startMin, end: 7 * 24 * 60 });
                        subIntervals.push({ row: row, start: 0, end: endMin - (7 * 24 * 60) });
                    } else {
                        subIntervals.push({ row: row, start: startMin, end: endMin });
                    }

                    // Check for overlap mathematically: Max(start1, start2) < Min(end1, end2)
                    subIntervals.forEach(newInt => {
                        intervals.forEach(existInt => {
                            if (existInt.row !== newInt.row) { 
                                if (Math.max(newInt.start, existInt.start) < Math.min(newInt.end, existInt.end)) {
                                    hasOverlap = true;
                                    newInt.row.classList.add('border-rose-400', 'bg-rose-50');
                                    existInt.row.classList.add('border-rose-400', 'bg-rose-50');
                                }
                            }
                        });
                        intervals.push(newInt);
                    });
                }
            });

            if (hasOverlap) {
                e.preventDefault(); // Stop form submission
                errorBlock.innerHTML = "<strong>Overlap Detected:</strong> Shift schedules cannot overlap or collide with each other. Please fix the highlighted rows.";
                errorBlock.classList.remove('hidden');
                errorBlock.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                errorBlock.classList.add('hidden');
            }
        });
    }
});