function showTab(tabId, element = null) {
  document.querySelectorAll('.tab').forEach(tab => tab.classList.add('hidden'));
  document.getElementById(tabId).classList.remove('hidden');

  if (element) {
    const buttons = document.querySelectorAll('#sidebarNav button');
    buttons.forEach(btn => {
      if (!btn.classList.contains('text-red-500')) {
        btn.classList.remove('bg-[#E17C5F]', 'text-white');
      }
    });

    if (!element.classList.contains('text-red-500')) {
      element.classList.add('bg-[#E17C5F]', 'text-white');
    }
  }

  // Auto-hide sidebar on small screens
  if (window.innerWidth < 768) {
    document.getElementById('sidebar')?.classList.add('-translate-x-full');
  }
}

window.addEventListener('DOMContentLoaded', () => {
  const defaultBtn = document.querySelector('#sidebarNav button:nth-child(1)');
  showTab('personal', defaultBtn);

  const toggleBtn = document.getElementById('toggleSidebar');
  const sidebar = document.getElementById('sidebar');

  toggleBtn?.addEventListener('click', () => {
    sidebar?.classList.toggle('-translate-x-full');
  });
});

function previewImage(event) {
  const reader = new FileReader();
  reader.onload = function () {
    const output = document.getElementById('profilePreview');
    output.src = reader.result;
  };
  reader.readAsDataURL(event.target.files[0]);
}
