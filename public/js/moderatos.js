  function showPanel(id) {
    document.querySelectorAll('.panel').forEach(p => p.classList.add('hidden'));
    document.getElementById(id).classList.remove('hidden');

    document.querySelectorAll('.nav-btn').forEach(btn => btn.classList.remove('bg-[#E17C5F]', 'text-white'));
    const activeBtn = document.getElementById('btn-' + id);
    if (activeBtn) {
      activeBtn.classList.add('bg-[#E17C5F]', 'text-white');
    }
  }

      function showPanel(id) {
      document.querySelectorAll('.panel').forEach(p => p.classList.add('hidden'));
      document.getElementById(id).classList.remove('hidden');

      document.querySelectorAll('.nav-btn').forEach(btn => btn.classList.remove('bg-[#E17C5F]', 'text-white'));
      const activeBtn = document.getElementById('btn-' + id);
      if (activeBtn) {
        activeBtn.classList.add('bg-[#E17C5F]', 'text-white');
      }
    }