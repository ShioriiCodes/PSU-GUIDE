  // Sidebar Toggle
  const sidebar = document.getElementById('sidebar');
  const toggleSidebarBtn = document.getElementById('toggleSidebar');
  toggleSidebarBtn?.addEventListener('click', () => {
    sidebar?.classList.toggle('-translate-x-full');
  });

  // Show Panel Logic
  function showPanel(id) {
    document.querySelectorAll('.panel').forEach(panel => panel.classList.add('hidden'));
    const active = document.getElementById(id);
    if (active) active.classList.remove('hidden');

    document.querySelectorAll('.nav-btn').forEach(btn => btn.classList.remove('bg-[#E17C5F]', 'text-white'));
    const clicked = document.querySelector(`#btn-${id}`);
    if (clicked) clicked.classList.add('bg-[#E17C5F]', 'text-white');

    if (window.innerWidth < 768) {
      sidebar?.classList.add('-translate-x-full');
    }

    history.replaceState(null, null, '#' + id);
  }

  // On Page Load
  document.addEventListener('DOMContentLoaded', function () {
    const hash = window.location.hash.replace('#', '') || 'stats';
    showPanel(hash);

    const form = document.getElementById('announcementForm');
    if (form) {
      form.addEventListener('submit', function (e) {
        const confirmed = confirm('Are you sure you want to submit this announcement for approval?');
        if (!confirmed) {
          e.preventDefault();
        }
      });
    }
  });

  // Filter Registrar Announcements
  function filterRegistrarAnnouncements() {
    const input = document.getElementById('registrarSearch');
    const filter = input.value.toLowerCase();
    const rows = document.querySelectorAll('#manage table tbody tr');

    rows.forEach(row => {
      const status = row.children[0]?.textContent.toLowerCase() || '';
      const title = row.children[1]?.textContent.toLowerCase() || '';
      const category = row.children[2]?.textContent.toLowerCase() || '';
      const date = row.children[3]?.textContent.toLowerCase() || '';
      const postedBy = row.children[4]?.textContent.toLowerCase() || '';
      const combined = `${status} ${title} ${category} ${date} ${postedBy}`;
      row.style.display = combined.includes(filter) ? '' : 'none';
    });
  }

  // Optional: General search fallback (if needed)
  function filterAnnouncements() {
    const input = document.getElementById('announcementSearch');
    const filter = input.value.toLowerCase();
    const rows = document.querySelectorAll('#manage table tbody tr');

    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      row.style.display = text.includes(filter) ? '' : 'none';
    });
  }

  // Preview Profile Image
  function previewProfileImage(input) {
    const preview = document.getElementById('profile-preview');
    const file = input.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = e => preview.src = e.target.result;
      reader.readAsDataURL(file);
    }
  }