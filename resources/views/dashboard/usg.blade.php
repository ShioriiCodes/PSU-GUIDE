<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>USG Moderator Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" href="{{ asset('logo/logo.ico') }}" type="image/png">
</head>
<body class="bg-[#F4E7E1] font-poppins">
  <div class="min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md p-6 space-y-4">
      <div class="flex items-center gap-4 mb-6">
        <a href="/" class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-gray-200 hover:bg-gray-300" title="Back to Home">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </a>
        <h2 class="text-xl font-semibold">USG Panel</h2>
      </div>
      <nav class="space-y-2">
        <button id="btn-stats" onclick="showPanel('stats')" class="nav-btn block w-full text-left px-4 py-2 rounded hover:bg-[#E17C5F] bg-[#E17C5F] text-white">Statistics</button>
        <button id="btn-create" onclick="showPanel('create')" class="nav-btn block w-full text-left px-4 py-2 rounded hover:bg-[#E17C5F] ">Post Announcement</button>
        <button id="btn-manage" onclick="showPanel('manage')" class="nav-btn block w-full text-left px-4 py-2 rounded hover:bg-[#E17C5F]">Manage Posts</button>
        <button id="btn-users" onclick="showPanel('users')" class="nav-btn block w-full text-left px-4 py-2 rounded hover:bg-[#E17C5F]">View Users</button>
        <button id="btn-settings" onclick="showPanel('settings')" class="nav-btn block w-full text-left px-4 py-2 rounded hover:bg-[#E17C5F]">Settings</button>
        <form id="logout-form" action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="nav-btn block w-full text-left px-4 py-2 rounded text-red-500 hover:bg-red-100">Logout</button>
        </form>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8">

  <!-- CREATE Announcement Panel -->
  <section id="create" class="panel hidden">
    <h1 class="text-2xl font-bold mb-6">Post New Announcement</h1>
    <div class="bg-white shadow-md rounded-lg p-6 max-w-3xl">
      <form id="announcementForm" action="{{ route('announcement.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 mt-[-50px]">
        @csrf
        <div>
          <label class="block text-sm font-medium mb-1">Title</label>
          <input name="title" type="text" class="w-full p-3 border border-gray-800 rounded" placeholder="Enter announcement title" required>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Message</label>
          <textarea name="content" class="w-full p-3 border border-gray-800 rounded h-40" placeholder="Write the announcement..." required></textarea>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Category</label>
          <select name="category_id" class="w-full p-2 border border-gray-800 rounded" required>
            @foreach($categories as $category)
              <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
          </select>
        </div>
        <button type="submit" class="px-6 py-2 bg-[#D5451B] text-white rounded hover:bg-[#aa3715]">Submit for Approval</button>
      </form>
    </div>
  </section>

  <!-- MANAGE Posts Panel -->
  <section id="manage" class="panel hidden">
    <h1 class="text-2xl font-bold mb-6">Manage Announcements</h1>
    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <input type="text" id="announcementSearch" placeholder="Search announcements..." class="w-full sm:w-1/2 p-2 border border-gray-300 rounded" onkeyup="filterAnnouncements()" />
    </div>
    <div class="bg-white rounded shadow overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Title</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Category</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Date</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Posted By</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @forelse ($announcements as $a)
          <tr>
            <td class="px-6 py-4">
              @if ($a->status === 'approved')
                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded">Approved</span>
              @elseif ($a->status === 'rejected')
                <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded">Rejected</span>
              @else
                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded">Pending</span>
              @endif
            </td>
            <td class="px-6 py-4 font-semibold">{{ $a->title }}</td>
            <td class="px-6 py-4">{{ $a->category->name ?? '—' }}</td>
            <td class="px-6 py-4">{{ $a->created_at->format('Y-m-d') }}</td>
            <td class="px-6 py-4">{{ $a->user->name ?? 'Unknown' }} ({{ $a->user->role ?? 'N/A' }})</td>
            <td class="px-6 py-4 space-x-2">
              <a href="{{ route('announcements.show', $a->id) }}" class="text-blue-600 hover:underline">View</a>
              <a href="{{ route('announcements.edit', $a->id) }}" class="text-yellow-600 hover:underline">Edit</a>
              <form method="POST" action="{{ route('announcements.destroy', $a->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this announcement?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:underline">Delete</button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No announcements found.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </section>

  <!-- USERS Panel -->
  <section id="users" class="panel hidden">
    <h1 class="text-2xl font-bold mb-6">Users</h1>
    <div class="flex flex-wrap gap-4">
      <div class="bg-white p-4 rounded-md shadow w-full sm:w-60 h-32 flex items-center justify-center text-center">
        <div><p class="text-lg font-semibold">Admins</p><p class="text-gray-600">{{ $admins }}</p></div>
      </div>
      <div class="bg-white p-4 rounded-md shadow w-full sm:w-60 h-32 flex items-center justify-center text-center">
        <div><p class="text-lg font-semibold">Registrars</p><p class="text-gray-600">{{ $registrars }}</p></div>
      </div>
      <div class="bg-white p-4 rounded-md shadow w-full sm:w-60 h-32 flex items-center justify-center text-center">
        <div><p class="text-lg font-semibold">USG Members</p><p class="text-gray-600">{{ $usgs }}</p></div>
      </div>
      <div class="bg-white p-4 rounded-md shadow w-full sm:w-60 h-32 flex items-center justify-center text-center">
        <div><p class="text-lg font-semibold">Faculty</p><p class="text-gray-600">{{ $faculty }}</p></div>
      </div>
      <div class="bg-white p-4 rounded-md shadow w-full sm:w-60 h-32 flex items-center justify-center text-center">
        <div><p class="text-lg font-semibold">Students</p><p class="text-gray-600">{{ $students }}</p></div>
      </div>
    </div>
  </section>

  <!-- STATS Panel -->
  <section id="stats" class="panel rounded-md">
    <h1 class="text-2xl font-bold mb-6">Announcement Statistics</h1>
    <div class="bg-white border border-gray-300 rounded-md overflow-hidden max-w-lg ml-0">
      <table class="w-full">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Metric</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Count</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr>
            <td class="px-6 py-4 text-gray-800 font-medium">Total Announcements</td>
            <td class="px-6 py-4">{{ $total }}</td>
          </tr>
          <tr>
            <td class="px-6 py-4 text-gray-800 font-medium">Approved</td>
            <td class="px-6 py-4 text-green-600 font-semibold">{{ $approved }}</td>
          </tr>
          <tr>
            <td class="px-6 py-4 text-gray-800 font-medium">Pending</td>
            <td class="px-6 py-4 text-yellow-600 font-semibold">{{ $pending }}</td>
          </tr>
          <tr>
            <td class="px-6 py-4 text-gray-800 font-medium">Rejected</td>
            <td class="px-6 py-4 text-red-600 font-semibold">{{ $rejected }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>

  <!-- SETTINGS Panel -->
  <section id="settings" class="panel hidden">
    <h1 class="text-2xl font-bold mb-6">USG Settings</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl">
      <!-- Profile Update -->
      <div class="bg-white p-6 rounded shadow border">
        <form method="POST" action="{{ route('user.update') }}" enctype="multipart/form-data" class="space-y-6">
          @csrf
          @method('PUT')
          <h2 class="text-xl font-semibold mb-4">Update Profile</h2>
          <div class="flex items-center gap-6">
            <img id="profile-preview" src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}" class="rounded-full w-24 h-24 object-cover border-2 border-gray-300 shadow" />
            <input type="file" name="profile_picture" accept="image/*" onchange="previewProfileImage(this)" class="text-sm">
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" class="w-full p-2 border rounded" required>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="w-full p-2 border rounded" required>
          </div>
          <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 bg-[#E17C5F] text-white rounded hover:bg-[#c05e4d]">Save Profile</button>
          </div>
        </form>
      </div>
      <!-- Password Change -->
      <div class="bg-white p-6 rounded shadow border">
        <form method="POST" action="{{ route('admin.updatePassword') }}" class="space-y-6">
          @csrf
          @method('PUT')
          <h2 class="text-xl font-semibold mb-4">Change Password</h2>
          <div>
            <label class="block text-sm font-medium mb-1">Current Password</label>
            <input type="password" name="current_password" class="w-full p-2 border border-gray-300 rounded" required>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">New Password</label>
            <input type="password" name="new_password" class="w-full p-2 border border-gray-300 rounded" required>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Confirm Password</label>
            <input type="password" name="new_password_confirmation" class="w-full p-2 border border-gray-300 rounded" required>
          </div>
          <div class="flex justify-end">
            <button class="px-4 py-2 bg-[#E17C5F] text-white rounded hover:bg-[#c05e4d]">Save Password</button>
          </div>
        </form>
      </div>
    </div>
  </section>
      <!-- Replace this comment with the panels from the Registrar layout -->

    </main>
  </div>

  <script>
      function showPanel(id) {
        document.querySelectorAll('.panel').forEach(panel => panel.classList.add('hidden'));
        const active = document.getElementById(id);
        if (active) active.classList.remove('hidden');

        document.querySelectorAll('.nav-btn').forEach(btn => btn.classList.remove('bg-[#E17C5F]', 'text-white'));
        const clicked = document.querySelector(`#btn-${id}`);
        if (clicked) clicked.classList.add('bg-[#E17C5F]', 'text-white');

        history.replaceState(null, null, '#' + id);
      }

      document.addEventListener('DOMContentLoaded', function () {
        const hash = window.location.hash.replace('#', '') || 'stats';
        showPanel(hash);
      });

      function filterAnnouncements() {
      const input = document.getElementById('announcementSearch');
      const filter = input.value.toLowerCase();
      const rows = document.querySelectorAll('#manage table tbody tr');

      rows.forEach(row => {
        const title = row.children[1]?.textContent.toLowerCase() || '';
        const category = row.children[2]?.textContent.toLowerCase() || '';
        const date = row.children[3]?.textContent.toLowerCase() || '';
        const postedBy = row.children[4]?.textContent.toLowerCase() || '';
        const status = row.children[0]?.textContent.toLowerCase() || '';

        const combined = [title, category, date, postedBy, status].join(' ');
        row.style.display = combined.includes(filter) ? '' : 'none';
      });
    }

  </script>

  <script src="{{ asset('js/moderatos.js') }}"></script>
</body>
</html>
