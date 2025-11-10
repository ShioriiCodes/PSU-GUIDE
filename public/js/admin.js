const sidebar = document.getElementById('sidebar');
const toggleSidebarBtn = document.getElementById('toggleSidebar');

toggleSidebarBtn.addEventListener('click', () => {
    sidebar.classList.toggle('-translate-x-full');
});

function previewProfileImage(input) {
    const preview = document.getElementById('profile-preview');
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => preview.src = e.target.result;
        reader.readAsDataURL(file);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const openPanel = json(session('openPanel'));
    if (openPanel) {
        showPanel(openPanel);
    }

    const successAlert = document.getElementById('alertSuccess');
    const errorAlert = document.getElementById('alertError');
    [successAlert, errorAlert].forEach(alert => {
        if (alert) {
            setTimeout(() => {
                alert.classList.add('opacity-0');
                setTimeout(() => alert.remove(), 1000);
            }, 3000);
        }
    });
});

function showPanel(panelId) {
    document.querySelectorAll('.panel').forEach(p => p.classList.add('hidden'));
    document.getElementById(panelId).classList.remove('hidden');

    document.querySelectorAll('.nav-btn').forEach(btn => btn.classList.remove('bg-[#E17C5F]', 'text-white'));
    const activeBtn = document.getElementById(`btn-${panelId}`);
    if (activeBtn) activeBtn.classList.add('bg-[#E17C5F]', 'text-white');

    if (window.innerWidth < 768) {
        sidebar.classList.add('-translate-x-full');
    }
}

let deleteFormToSubmit = null;

function showDeleteModal(event) {
    event.preventDefault();
    deleteFormToSubmit = event.target;
    const userName = deleteFormToSubmit.dataset.name || 'this account';
    document.getElementById('deleteTargetName').textContent = userName;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex', 'items-center', 'justify-center');
    return false;
}

function closeDeleteModal() {
    deleteFormToSubmit = null;
    document.getElementById('deleteModal').classList.add('hidden');
}

function submitDeleteForm() {
    if (deleteFormToSubmit) {
        deleteFormToSubmit.submit();
    }
}

function toggleImportForm() {
    const form = document.getElementById('importStudentForm');
    form.classList.toggle('hidden');
}

function toggleImportFacultyForm() {
    const form = document.getElementById('importFacultyForm');
    form.classList.toggle('hidden');
}

function showImportStudentModal() {
    const modal = document.getElementById('importStudentModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function closeImportStudentModal() {
    const modal = document.getElementById('importStudentModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

function showImportFacultyModal() {
    const modal = document.getElementById('importFacultyModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function closeImportFacultyModal() {
    const modal = document.getElementById('importFacultyModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

function filterTableRows(inputId, rowSelector) {
    const input = document.getElementById(inputId);
    const filter = input.value.toLowerCase();
    const rows = document.querySelectorAll(rowSelector);
    rows.forEach(row => {
        const rowText = row.textContent.toLowerCase();
        row.style.display = rowText.includes(filter) ? '' : 'none';
    });
}

function filterCards(inputId, cardSelector) {
    const input = document.getElementById(inputId);
    const filter = input.value.toLowerCase();
    const cards = document.querySelectorAll(cardSelector);
    let visibleCards = 0;

    cards.forEach(card => {
        const cardText = card.textContent.toLowerCase();
        if (cardText.includes(filter)) {
            card.style.display = '';
            visibleCards++;
        } else {
            card.style.display = 'none';
        }
    });

    // Handle empty state message
    const gridContainer = document.querySelector(cardSelector.replace(' > div', ''));
    let emptyMessage = gridContainer?.querySelector('.empty-message');

    if (gridContainer && !emptyMessage) {
        // Create empty message if it doesn't exist
        const noResultsMsg = document.createElement('p');
        noResultsMsg.className = 'empty-message text-gray-500 col-span-full text-center hidden';
        noResultsMsg.textContent = 'No results found.';
        gridContainer.appendChild(noResultsMsg);
        emptyMessage = noResultsMsg;
    }

    if (emptyMessage) {
        if (filter.trim() === '') {
            // If no search term, hide the empty message (original state)
            emptyMessage.style.display = 'none';
        } else if (visibleCards === 0) {
            // If searching and no results, show "No results found"
            emptyMessage.textContent = 'No results found.';
            emptyMessage.style.display = 'block';
        } else {
            // If there are results, hide the empty message
            emptyMessage.style.display = 'none';
        }
    }
}

// --- Students multi-course filtering + search ---
let selectedStudentCourses = new Set();

function toggleCourseFilterDropdown() {
    const dd = document.getElementById('courseFilterDropdown');
    if (dd) dd.classList.toggle('hidden');
}

function onCourseFilterChange(checkbox) {
    const value = checkbox.value;
    if (checkbox.checked) {
        selectedStudentCourses.add(value);
    } else {
        selectedStudentCourses.delete(value);
    }
    renderCourseChips();
    applyStudentFilters();
}

function clearAllCourseFilters() {
    selectedStudentCourses.clear();
    // Uncheck all
    document.querySelectorAll('#courseFilterDropdown input[type="checkbox"]').forEach(cb => cb.checked = false);
    renderCourseChips();
    applyStudentFilters();
}

function removeCourseChip(course) {
    selectedStudentCourses.delete(course);
    const cb = document.querySelector(`#courseFilterDropdown input[type="checkbox"][value="${CSS.escape(course)}"]`);
    if (cb) cb.checked = false;
    renderCourseChips();
    applyStudentFilters();
}

function renderCourseChips() {
    const container = document.getElementById('activeCourseChips');
    if (!container) return;
    container.innerHTML = '';
    if (selectedStudentCourses.size === 0) {
        container.style.display = 'none';
        return;
    }
    container.style.display = '';
    selectedStudentCourses.forEach(course => {
        const chip = document.createElement('span');
        chip.className = 'inline-flex items-center gap-2 px-2 py-1 text-xs rounded-full bg-slate-100 text-slate-700 border border-slate-200';
        chip.innerHTML = `${course} <button type="button" class="ml-1 text-slate-500 hover:text-slate-700" aria-label="Remove ${course}" onclick="removeCourseChip('${course.replace(/'/g, "&#39;")}')">✕</button>`;
        container.appendChild(chip);
    });
}

function applyStudentFilters() {
    const searchEl = document.getElementById('studentSearch');
    const query = (searchEl?.value || '').trim().toLowerCase();
    const rows = document.querySelectorAll('#students table tbody tr');

    const hasCourseFilter = selectedStudentCourses.size > 0;

    rows.forEach(row => {
        // Skip empty-state rows
        if (!row.querySelector('td')) return;

        const course = (row.getAttribute('data-course') || '').toLowerCase();
        // Filter by course selection (if any)
        if (hasCourseFilter && !selectedStudentCourses.has((row.getAttribute('data-course') || '').toUpperCase())) {
            row.style.display = 'none';
            return;
        }

        if (query === '') {
            row.style.display = '';
            return;
        }

        // When course filters are active, search only within the currently filtered set.
        // We still check row text but result is already constrained by course above.
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(query) ? '' : 'none';
    });
}

// Close dropdown when clicking outside
document.addEventListener('click', (e) => {
    const dd = document.getElementById('courseFilterDropdown');
    const btn = document.getElementById('courseFilterBtn');
    if (!dd || !btn) return;
    if (!dd.classList.contains('hidden') && !dd.contains(e.target) && !btn.contains(e.target)) {
        dd.classList.add('hidden');
    }
});
