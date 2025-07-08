<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registrar Moderator Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" href="{{ asset('logo/logo.ico') }}" type="image/png">
</head>
<body class="bg-[#F4E7E1] font-poppins">
  <div class="min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md p-6 space-y-4">
      <div class="flex items-center gap-4 mb-6">
        <a href="/index.html" class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-gray-200 hover:bg-gray-300" title="Back to Home">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </a>
        <h2 class="text-xl font-semibold">Registrar Panel</h2>
      </div>
      <nav class="space-y-2">
        <button id="btn-create" onclick="showPanel('create')" class="nav-btn block w-full text-left px-4 py-2 rounded hover:bg-[#E17C5F] bg-[#E17C5F] text-white">Post Announcement</button>
        <button id="btn-manage" onclick="showPanel('manage')" class="nav-btn block w-full text-left px-4 py-2 rounded hover:bg-[#E17C5F]">Manage Posts</button>
        <form id="logout-form" action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-btn block w-full text-left px-4 py-2 rounded text-red-500 hover:bg-red-100">Logout</button>
        </form>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8">
      <!-- Create Announcement Panel -->
      <section id="create" class="panel">
        <h1 class="text-2xl font-bold mb-6">Post New Announcement</h1>
        <form class="space-y-6 max-w-2xl">
          <div>
            <label class="block text-sm font-medium mb-1">Title</label>
            <input type="text" class="w-full p-3 border border-gray-800 rounded" placeholder="Enter announcement title">
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Message</label>
            <textarea class="w-full p-3 border border-gray-800 rounded h-40" placeholder="Write the announcement..."></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Category</label>
            <select class="w-full p-2 border border-gray-800 rounded">
              <option>Enrollment</option>
              <option>Schedule</option>
              <option>Official Notice</option>
              <option>Deadline</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Post Date</label>
            <input type="date" class="w-full p-2 border border-gray-800 rounded">
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Attach Poster (optional)</label>
            <input type="file" class="w-full border border-gray-800 rounded p-2">
          </div>
          <button type="submit" class="px-6 py-2 bg-[#D5451B] text-white rounded hover:bg-[#aa3715]">Publish</button>
        </form>
      </section>

      <!-- Manage Announcements Panel -->
      <section id="manage" class="panel hidden">
        <h1 class="text-2xl font-bold mb-6">Manage Announcements</h1>
        <p class="text-gray-700">This section will show a list of announcements posted by the registrar. Future features may include editing, deleting, or scheduling.</p>
      </section>

      <!-- Logout Panel -->
      <section id="logout" class="panel hidden">
        <h1 class="text-xl font-bold text-red-600">You have logged out successfully.</h1>
      </section>
    </main>
  </div>

  <script src="/assets/js/moderatos.js"></script>
</body>
</html>
