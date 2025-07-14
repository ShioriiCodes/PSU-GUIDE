
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
        <a href="/" class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-gray-200 hover:bg-gray-300" title="Back to Home">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </a>
        <h2 class="text-xl font-semibold">Registrar Panel</h2>
      </div>
      <nav class="space-y-2">
        <button id="btn-create" onclick="showPanel('create')" class="nav-btn block w-full text-left px-4 py-2 rounded hover:bg-[#E17C5F] bg-[#E17C5F] text-white">Post Announcement</button>
        <button id="btn-manage" onclick="showPanel('manage')" class="nav-btn block w-full text-left px-4 py-2 rounded hover:bg-[#E17C5F]">Manage Posts</button>
        <button id="btn-users" onclick="showPanel('users')" class="nav-btn block w-full text-left px-4 py-2 rounded hover:bg-[#E17C5F]">View Users</button>
        <button id="btn-stats" onclick="showPanel('stats')" class="nav-btn block w-full text-left px-4 py-2 rounded hover:bg-[#E17C5F]">Statistics</button>
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

      <div class="bg-white shadow-md rounded-lg p-6 max-w-3xl">
        <form id="announcementForm" action="{{ route('announcement.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
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
            @php
              $allowedRegistrarCategories = [
                'Registrar Notices',
                'Enrollment Schedules',
                'Academic Deadlines',
                'Faculty Meetings & Assemblies'
              ];
            @endphp
            <select name="category_id" class="w-full p-2 border border-gray-800 rounded" required>
              @foreach($categories as $category)
                @if(in_array($category->name, $allowedRegistrarCategories))
                  <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endif
              @endforeach
            </select>
          </div>
          <button type="submit" class="px-6 py-2 bg-[#D5451B] text-white rounded hover:bg-[#aa3715]">Submit for Approval</button>
        </form>
      </div>
    </section>


    <!-- Confirmation Script -->
    <script>
      document.getElementById('announcementForm').addEventListener('submit', function (e) {
        const confirmed = confirm('Are you sure you want to submit this announcement for approval?');
        if (!confirmed) {
          e.preventDefault();
        }
      });
    </script>


    <!-- Manage Announcements Panel (Registrar) -->
    <section id="manage" class="panel hidden">
      <h1 class="text-2xl font-bold mb-6">Manage Announcements</h1>

      <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <input type="text" placeholder="Search announcements..." class="w-full sm:w-1/2 p-2 border border-gray-300 rounded" />
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
              <td class="px-6 py-4">
                {{ $a->user->name ?? 'Unknown' }} ({{ $a->user->role ?? 'N/A' }})
              </td>
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

    <!-- Users Panel -->
    <section id="users" class="panel hidden">
      <h1 class="text-2xl font-bold mb-6">Users</h1>
      <div class="flex flex-wrap gap-4">
        <div class="bg-white p-4 rounded-md shadow w-full sm:w-60 h-32 flex items-center justify-center text-center">
          <div>
            <p class="text-lg font-semibold">Admins</p>
            <p class="text-gray-600">{{ $admins }}</p>
          </div>
        </div>
        <div class="bg-white p-4 rounded-md shadow w-full sm:w-60 h-32 flex items-center justify-center text-center">
          <div>
            <p class="text-lg font-semibold">Registrars</p>
            <p class="text-gray-600">{{ $registrars }}</p>
          </div>
        </div>
        <div class="bg-white p-4 rounded-md shadow w-full sm:w-60 h-32 flex items-center justify-center text-center">
          <div>
            <p class="text-lg font-semibold">USG Members</p>
            <p class="text-gray-600">{{ $usgs }}</p>
          </div>
        </div>
        <div class="bg-white p-4 rounded-md shadow w-full sm:w-60 h-32 flex items-center justify-center text-center">
          <div>
            <p class="text-lg font-semibold">Faculty</p>
            <p class="text-gray-600">{{ $faculty }}</p>
          </div>
        </div>
        <div class="bg-white p-4 rounded-md shadow w-full sm:w-60 h-32 flex items-center justify-center text-center">
          <div>
            <p class="text-lg font-semibold">Students</p>
            <p class="text-gray-600">{{ $students }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Stats Panel -->
    <section id="stats" class="panel hidden rounded-md">
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

    </main>
  </div>

  <script src="{{ asset('js/moderatos.js') }}"></script>
</body>
</html>
