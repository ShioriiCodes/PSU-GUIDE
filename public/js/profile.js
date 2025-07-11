  // Handle tab switching in profile
  function showTab(tabId, element = null) {
    // 1. Hide all tabs
    document.querySelectorAll('.tab').forEach(tab => tab.classList.add('hidden'));

    // 2. Show selected tab
    document.getElementById(tabId).classList.remove('hidden');

    // 3. Highlight selected button unless it's logout
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
  }

  // Default to Personal Info on page load
  window.addEventListener('DOMContentLoaded', () => {
    const defaultBtn = document.querySelector('#sidebarNav button[data-tab="personal"]');
    if (defaultBtn) {
      showTab('personal', defaultBtn);
    }
  });

  // Profile picture preview
  function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function () {
      const output = document.getElementById('profilePreview');
      if (output) {
        output.src = reader.result;
      }
    };
    reader.readAsDataURL(event.target.files[0]);
  }