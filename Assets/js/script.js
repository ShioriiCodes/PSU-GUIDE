  const links = document.querySelectorAll('#nav-links .nav-link');
  const currentPage = window.location.pathname.split("/").pop();

  links.forEach(link => {
    const href = link.getAttribute('href');
    if (href === currentPage || (href === "index.html" && currentPage === "")) {
      link.classList.add("border-[#E17C5F]");
    }
  });


  // Announcement Page Script
  const buttons = document.querySelectorAll('.filter-btn');
  const cards = document.querySelectorAll('.announcement-card');

  buttons.forEach(btn => {
    btn.addEventListener('click', () => {
      // Remove active styles from all buttons
      buttons.forEach(b => {
        b.classList.remove('bg-[#D5451B]', 'text-white');
        b.classList.add('bg-white', 'text-[#521C0D]');
      });

      // Apply active style to clicked button
      btn.classList.remove('bg-white', 'text-[#521C0D]');
      btn.classList.add('bg-[#D5451B]', 'text-white');

      const filter = btn.getAttribute('data-filter');

      // Show/hide cards
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

// Login Page Script
  let isPasswordVisible = false;
  function togglePassword() {
    const password = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');

    if (isPasswordVisible) {
      password.type = 'password';
      toggleIcon.src = '/assets/image/icon/open-eye.png';
    } else {
      password.type = 'text';
      toggleIcon.src = '/assets/image/icon/closed-eye.png';
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