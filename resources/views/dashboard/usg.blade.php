<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>USG Moderator Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
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
        <h2 class="text-xl font-semibold">USG Panel</h2>
      </div>
      <nav class="space-y-2">
        <button id="btn-create" onclick="showPanel('create')" class="nav-btn block w-full text-left px-4 py-2 rounded hover:bg-[#E17C5F] bg-[#E17C5F] text-white">Create Update</button>
        <button id="btn-manage" onclick="showPanel('manage')" class="nav-btn block w-full text-left px-4 py-2 rounded hover:bg-[#E17C5F]">Manage Updates</button>
        <form id="logout-form" action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-btn block w-full text-left px-4 py-2 rounded text-red-500 hover:bg-red-100">Logout</button>
        </form>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8">
      <!-- Create Update Panel -->
      <section id="create" class="panel">
        <h1 class="text-2xl font-bold mb-6">Create Student Update</h1>
        <form class="space-y-6 max-w-2xl">
          <div>
            <label class="block text-sm font-medium mb-1">Title</label>
            <input type="text" class="w-full p-3 border border-gray-800 rounded" placeholder="Enter update title">
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Message</label>
            <textarea class="w-full p-3 border border-gray-800 rounded h-40" placeholder="Write your message..."></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Category</label>
            <select class="w-full p-2 border border-gray-800 rounded">
              <option>Event</option>
              <option>Student Life</option>
              <option>Reminder</option>
              <option>Elections</option>
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
          <button type="submit" class="px-6 py-2 bg-[#D5451B] text-white rounded hover:bg-[#aa3715]">Post Update</button>
        </form>
      </section>

      <!-- Manage Updates Panel -->
      <section id="manage" class="panel hidden">
        <h1 class="text-2xl font-bold mb-6">Manage Student Updates</h1>
        <p class="text-gray-700">This section will show a list of posts made by USG. Moderators can edit or delete previous announcements here.</p>
      </section>

      <!-- Logout Panel -->
      <section id="logout" class="panel hidden">
        <h1 class="text-xl font-bold text-red-600">You have logged out successfully.</h1>
      </section>
    </main>
  </div>

  <script src="{{ asset('js/moderatos.js') }}"></script>
</body>
</html>
