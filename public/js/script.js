  // === Profile Panel: Tab Switching & Preview ===
  function showTab(tabId, element = null) {
    document.querySelectorAll('.tab').forEach(tab => tab.classList.add('hidden'));
    const activeTab = document.getElementById(tabId);
    if (activeTab) activeTab.classList.remove('hidden');

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

  function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function () {
      const output = document.getElementById('profilePreview');
      output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  }

  function toggleProfile() {
    const panel = document.getElementById('profilePanel');
    panel.classList.toggle('hidden');
  }

  function setupTwoFactorToggle() {
    const twoFactorCheckbox = document.getElementById('enable2fa');
    if (twoFactorCheckbox) {
      twoFactorCheckbox.addEventListener('change', () => {
        const setupSection = document.getElementById('2fa-setup');
        if (setupSection) {
          setupSection.classList.toggle('hidden', !twoFactorCheckbox.checked);
        }
      });
    }
  }

  // === Login Page ===
  let isPasswordVisible = false;
  function togglePassword() {
    const password = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    if (isPasswordVisible) {
      password.type = 'password';
      toggleIcon.src = '/image/icon/open-eye.png';
    } else {
      password.type = 'text';
      toggleIcon.src = '/image/icon/closed-eye.png';
    }
    isPasswordVisible = !isPasswordVisible;
  }

  function validateLogin() {
    const studentNumber = document.getElementById('studentNumber');
    const password = document.getElementById('password');
    const errorBox = document.getElementById('errorBox');

    if (!studentNumber.value.trim() || !password.value.trim()) {
      errorBox.textContent = 'Please enter both Student Number and Password.';
      errorBox.classList.remove('hidden');
      return;
    }

    errorBox.classList.add('hidden');
    alert('Login successful for ' + studentNumber.value);
  }

  // === DOMContentLoaded Initialization ===
  document.addEventListener("DOMContentLoaded", () => {
    // 1. Default tab in profile panel
    const defaultBtn = document.querySelector('#sidebarNav button:nth-child(1)');
    if (defaultBtn) showTab('personal', defaultBtn);

    // 2. Two-factor checkbox setup
    setupTwoFactorToggle();

    // 3. Nav highlight logic
    const links = document.querySelectorAll('#nav-links .nav-link');
    const currentPath = window.location.pathname.replace(/\/$/, "");
    let matched = false;

    links.forEach(link => {
      const linkPath = new URL(link.href).pathname.replace(/\/$/, "");
      if (linkPath === currentPath) {
        link.classList.add("border-[#F15B24]");
        matched = true;
      }
    });

    if (!matched && (currentPath === "" || currentPath === "/")) {
      const homeLink = document.getElementById("nav-home");
      if (homeLink) homeLink.classList.add("border-[#F15B24]");
    }

    // 4. Mobile nav toggle
    const toggleBtn = document.getElementById('menu-toggle');
    const navLinks = document.getElementById('nav-links');
    if (toggleBtn && navLinks) {
      toggleBtn.addEventListener('click', () => {
        navLinks.classList.toggle('hidden');
      });
    }

    // 5. Announcement Page Filtering
    const buttons = document.querySelectorAll('.filter-btn');
    const cards = document.querySelectorAll('.announcement-card');

    buttons.forEach(btn => {
      btn.addEventListener('click', () => {
        buttons.forEach(b => {
          b.classList.remove('bg-[#D5451B]', 'text-white');
          b.classList.add('bg-white', 'text-[#521C0D]');
        });

        btn.classList.remove('bg-white', 'text-[#521C0D]');
        btn.classList.add('bg-[#D5451B]', 'text-white');

        const filter = btn.getAttribute('data-filter');

        cards.forEach(card => {
          const category = card.getAttribute('data-category');
          if (filter === 'All' || category === filter) {
            card.classList.remove('hidden');
          } else {
            card.classList.add('hidden');
          }
        });
      });
    });
  });