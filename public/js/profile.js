  function showTab(tabId, element = null) {
    // 1. Show selected content tab
    document.querySelectorAll('.tab').forEach(tab => tab.classList.add('hidden'));
    document.getElementById(tabId).classList.remove('hidden');

    // 2. Highlight active button (skip if Logout)
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

  // 3. Default to Personal Info on page load
  window.addEventListener('DOMContentLoaded', () => {
    const defaultBtn = document.querySelector('#sidebarNav button:nth-child(1)');
    showTab('personal', defaultBtn);
  });

  // Optional: Profile image preview
  function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
      const output = document.getElementById('profilePreview');
      output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  }