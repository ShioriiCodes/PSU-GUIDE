<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>USG Moderator Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" href="{{ asset('logo/logo.ico') }}" type="image/png">
    <style>
        body {
        font-family: 'Inter', sans-serif;
        }
        .nav-btn {
        transition: all 0.3s ease;
        }
        .panel {
        min-height: 100vh;
        }
        .shadow-custom {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-slate-100 font-sans min-h-screen">

  <div class="min-h-screen flex flex-col lg:flex-row relative">
        <button id="toggleSidebar" class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-white rounded-lg shadow-custom">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
        <!-- Sidebar -->
        <aside id="sidebar" class="w-full lg:w-64 xl:w-72 bg-white shadow-xl p-6 space-y-6 fixed lg:static top-0 left-0 h-full lg:h-auto overflow-y-auto transform -translate-x-full lg:translate-x-0 transition-transform duration-300 z-30 pt-16 lg:pt-6">
          <div class="flex items-center gap-4 mb-8">
            <a href="/" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-slate-200 hover:bg-slate-300 transition" title="Back to Home">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
            </a>
            <h2 class="text-xl font-bold text-slate-800">USG Panel</h2>
          </div>
          <nav class="space-y-3">
            <button id="btn-stats" onclick="showPanel('stats')" class="nav-btn w-full text-left px-4 py-3 rounded-lg  hover:bg-slate-200 transition flex items-center gap-3">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
              </svg>
              Statistics
            </button>
            <button id="btn-create" onclick="showPanel('create')" class="nav-btn w-full text-left px-4 py-3 rounded-lg hover:bg-slate-200 transition flex items-center gap-3">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
              </svg>
              Post Announcement
            </button>
            <button id="btn-manage" onclick="showPanel('manage')" class="nav-btn w-full text-left px-4 py-3 rounded-lg hover:bg-slate-200 transition flex items-center gap-3">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
              All Posts
            </button>
            <button id="btn-settings" onclick="showPanel('settings')" class="nav-btn w-full text-left px-4 py-3 rounded-lg hover:bg-slate-200 transition flex items-center gap-3">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              Settings
            </button>
            <form id="logout-form" action="{{ route('logout') }}" method="POST">
              @csrf
              <button type="submit" class="nav-btn w-full text-left px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 transition flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Logout
              </button>
            </form>
          </nav>
        </aside>

        <!-- Main Content -->
    <main class="flex-1 p-4 sm:p-6 lg:p-8 z-10 min-h-screen overflow-y-auto">

        <!-- CREATE Announcement Panel -->
        <section id="create" class="panel hidden pt-5">
          <div class="bg-white shadow-md rounded-lg px-6 py-5 max-w-2xl mx-auto">
            <h1 class="text-xl font-bold mb-4 text-center">Post New Announcement</h1>
            <form id="announcementForm"
                  action="{{ route('announcement.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="space-y-4 w-full" onsubmit="return confirmSubmission()">
              @csrf
              @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                  <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif
              <!-- Title -->
              <div>
                <label class="block text-sm font-medium mb-1">Title</label>
                <input name="title" type="text"
                      class="w-full p-2 border border-gray-800 rounded focus:outline-none focus:ring-2 focus:ring-orange-400"
                      placeholder="Enter announcement title"
                      required>
              </div>

              <!-- Content -->
              <div>
                <label class="block text-sm font-medium mb-1">Content</label>
                <textarea name="content"
                        class="w-full p-2 border border-gray-800 rounded focus:outline-none focus:ring-2 focus:ring-orange-400 resize-vertical"
                        rows="6"
                        placeholder="Enter announcement content..."
                        required></textarea>
              </div>

              <!-- Poster Image or PDF (optional) -->
              <div>
                <label class="block text-sm font-medium mb-1">Poster Image or PDF (optional)</label>
                <input name="poster_image" type="file"
                      accept="image/*,.pdf"
                      class="w-full p-2 border border-gray-800 rounded focus:outline-none focus:ring-2 focus:ring-orange-400">
                <p class="text-xs text-gray-500 mt-1">Accepted formats: Images (JPG, PNG, etc.) and PDF files.</p>
              </div>

              <!-- Multiple Images (new) -->
              <div>
                <label class="block text-sm font-medium mb-1">Additional Images (you can select multiple)</label>
                <input name="poster_images[]" type="file" multiple
                      accept="image/*"
                      class="w-full p-2 border border-gray-800 rounded focus:outline-none focus:ring-2 focus:ring-orange-400">
                <p class="text-xs text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple images. Up to 10 images.</p>
              </div>

              <!-- Category and Custom Category -->
              <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium mb-1">Category</label>
                  <select name="category_id" id="category_id"
                          class="w-full p-2 border border-gray-800 rounded focus:outline-none focus:ring-2 focus:ring-orange-400"
                          required onchange="toggleCustomCategory()">
                    @foreach($categories as $category)
                      <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div id="customCategoryDiv" class="hidden">
                  <label class="block text-sm font-medium mb-1">Custom Category</label>
                  <input name="custom_category" type="text" id="custom_category"
                        class="w-full p-2 border border-gray-800 rounded focus:outline-none focus:ring-2 focus:ring-orange-400"
                        placeholder="Enter custom category name" required>
                </div>
              </div>

              <!-- Submit Button -->
              <div class="text-right">
                <button type="submit"
                        class="px-5 py-2 bg-[#D5451B] text-white rounded hover:bg-[#aa3715] transition duration-200">
                  Submit for Approval
                </button>
              </div>
            </form>
          </div>
        </section>

          <!-- MANAGE Posts Panel -->
        <section id="manage" class="panel hidden pt-4">
          <h1 class="text-2xl font-bold mb-6">All My Posts</h1>

          <!-- Search bar -->
          <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
            <input type="text" id="announcementSearch"
                  placeholder="Search announcements..."
                  class="w-full lg:w-1/2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400"
                  onkeyup="filterAnnouncements()" />
          </div>

          <!-- Scrollable Table Container -->
          <div class="bg-white rounded shadow w-full overflow-x-auto">
            <!-- Table on medium+ screens -->
              <div class="hidden lg:block w-full overflow-x-auto">
                <table class="table-auto min-w-full text-sm border border-gray-200">
                  <thead class="bg-gray-100 text-xs sm:text-sm">
                    <tr>
                      <th class="px-4 py-2 text-left text-gray-700 whitespace-nowrap">Status</th>
                      <th class="px-4 py-2 text-left text-gray-700 whitespace-nowrap">Title</th>
                      <th class="px-4 py-2 text-left text-gray-700 whitespace-nowrap">Category</th>
                      <th class="px-4 py-2 text-left text-gray-700 whitespace-nowrap">Date</th>
                      <th class="px-4 py-2 text-left text-gray-700 whitespace-nowrap">Posted By</th>
                      <th class="px-4 py-2 text-left text-gray-700 whitespace-nowrap">Actions</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($announcements as $a)
                      <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3">
                          @if ($a->status === 'approved')
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded">Approved</span>
                          @elseif ($a->status === 'rejected')
                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded">Rejected</span>
                          @else
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded">Pending</span>
                          @endif
                        </td>
                        <td class="px-4 py-3 font-semibold break-words max-w-[12rem]">{{ $a->title }}</td>
                        <td class="px-4 py-3">{{ $a->category->name ?? '—' }}{{ $a->custom_category ? ' (' . $a->custom_category . ')' : '' }}</td>
                        <td class="px-4 py-3">{{ $a->created_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-3">
                          {{ $a->user->name ?? 'Unknown' }}<br />
                          <span class="text-xs text-gray-500">({{ $a->user->role ?? 'N/A' }})</span>
                        </td>
                        <td class="px-4 py-3 space-x-2 text-sm">
                          @if($a->status === 'rejected')
                            @php
                              $approval = $a->approvals()->where('status', 'rejected')->first();
                            @endphp
                            <button onclick="showRejectionReasonModal({{ json_encode($approval->rejection_reason ?? 'No reason provided') }})"
                                  class="px-3 py-1 bg-red-100 text-red-700 text-xs rounded hover:bg-red-200 transition">
                              View Reason
                            </button>
                            <a href="{{ route('announcements.edit', $a->id) }}" class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs rounded hover:bg-yellow-200 transition">Edit</a>
                          @endif
                          <a href="{{ route('announcements.show', $a->id) }}" class="px-3 py-1 bg-blue-100 text-blue-700 text-xs rounded hover:bg-blue-200 transition">View</a>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="6" class="px-4 py-4 text-center text-gray-500">No announcements found.</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>

              <!-- Responsive Cards on small screens -->
              <div class="lg:hidden space-y-4">
                @forelse ($announcements as $a)
                  <div class="border border-gray-200 rounded-lg p-4 shadow-sm bg-white">
                    <div class="flex justify-between items-center mb-2">
                      <span class="text-xs font-semibold uppercase text-gray-500">Status:</span>
                      <span class="text-xs px-2 py-1 rounded
                        {{ $a->status === 'approved' ? 'bg-green-100 text-green-700' :
                          ($a->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                        {{ ucfirst($a->status) }}
                      </span>
                    </div>
                    <div class="text-sm mb-1"><strong>Title:</strong> {{ $a->title }}</div>
                    <div class="text-sm mb-1"><strong>Category:</strong> {{ $a->category->name ?? '—' }}{{ $a->custom_category ? ' (' . $a->custom_category . ')' : '' }}</div>
                    <div class="text-sm mb-1"><strong>Date:</strong> {{ $a->created_at->format('Y-m-d') }}</div>
                    <div class="text-sm mb-3"><strong>Posted By:</strong> {{ $a->user->name ?? 'Unknown' }} ({{ $a->user->role ?? 'N/A' }})</div>
                    <div class="flex flex-wrap gap-3 text-sm">
                      @if($a->status === 'rejected')
                        @php
                          $approval = $a->approvals()->where('status', 'rejected')->first();
                        @endphp
                        <button onclick="showRejectionReasonModal({{ json_encode($approval->rejection_reason ?? 'No reason provided') }})"
                              class="px-3 py-1 bg-red-100 text-red-700 text-xs rounded hover:bg-red-200 transition">
                          View Reason
                        </button>
                        <a href="{{ route('announcements.edit', $a->id) }}" class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs rounded hover:bg-yellow-200 transition">Edit</a>
                      @endif
                      <a href="{{ route('announcements.show', $a->id) }}" class="px-3 py-1 bg-blue-100 text-blue-700 text-xs rounded hover:bg-blue-200 transition">View</a>
                    </div>
                  </div>
                @empty
                  <p class="text-gray-500 text-center">No announcements found.</p>
                @endforelse
              </div>
          </div>
        </section>

        <!-- Rejection Reason Modal -->
        <div id="rejectionReasonModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[80] px-4 ">
            <div class="bg-white p-8 rounded-2xl w-full max-w-2xl shadow-2xl relative">
                <button onclick="closeRejectionReasonModal()"
                        class="absolute top-4 right-6 text-gray-400 hover:text-black text-3xl font-bold transition z-[90]">
                    &times;
                </button>

                <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Rejection Reason</h2>

                <div class="mb-6">
                    <p id="rejectionReasonContent" class="text-gray-700 leading-relaxed whitespace-pre-line">
                        Rejection reason will appear here...
                    </p>
                </div>

                <div class="flex justify-end">
                    <button type="button" onclick="closeRejectionReasonModal()"
                            class="px-6 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">
                        Close
                    </button>
                </div>
            </div>
        </div>

      <!-- STATS Panel -->
      <section id="stats" class="panel rounded-md pt-4">
        <h1 class="text-3xl font-bold mb-8 text-slate-800">Announcement Statistics</h1>
        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden max-w-lg ml-0 shadow-custom">
          <table class="w-full">
            <thead class="bg-slate-100">
              <tr>
                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700 flex items-center gap-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                  </svg>
                  Metric
                </th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Count</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
              <tr>
                <td class="px-6 py-4 text-slate-800 font-medium">Total Announcements</td>
                <td class="px-6 py-4">{{ $total }}</td>
              </tr>
              <tr>
                <td class="px-6 py-4 text-slate-800 font-medium">Approved</td>
                <td class="px-6 py-4 text-green-600 font-semibold">{{ $approved }}</td>
              </tr>
              <tr>
                <td class="px-6 py-4 text-slate-800 font-medium">Pending</td>
                <td class="px-6 py-4 text-yellow-600 font-semibold">{{ $pending }}</td>
              </tr>
              <tr>
                <td class="px-6 py-4 text-slate-800 font-medium">Rejected</td>
                <td class="px-6 py-4 text-red-600 font-semibold">{{ $rejected }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- SETTINGS Panel -->
      <section id="settings" class="panel hidden pt-4">
        <h1 class="text-2xl font-bold mb-6">USG Settings</h1>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-6xl">
          <!-- Profile Update -->
          <div class="bg-white p-6 rounded shadow border">
            <form method="POST" action="{{ route('user.update') }}" enctype="multipart/form-data" class="space-y-6">
              @csrf
              @method('PUT')
              <h2 class="text-xl font-semibold mb-4">Update Profile</h2>
              @php
                  $usgProfile = Auth::user()->profile_picture ?? null;
                  $usgProfilePath = $usgProfile ? 'profile_pictures/' . basename($usgProfile) : null;
                  $usgProfileUrl = $usgProfilePath
                      ? asset('storage/' . $usgProfilePath)
                      : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name);
              @endphp
              <div class="flex items-center gap-6">
                <img id="profile-preview" src="{{ $usgProfileUrl }}" class="rounded-full w-24 h-24 object-cover border-2 border-gray-300 shadow" />
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
          {{-- <!-- Password Change -->
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
          </div> --}}
        </div>
      </section>
      <!-- Replace this comment with the panels from the Registrar layout -->

    </main>
  </div>

  <script src="{{ asset('js/moderatos.js') }}"></script>

  <script>
    // function confirmSubmission() {
    //   return confirm('Are you sure you want to submit this announcement for approval?');
    // }
    function toggleCustomCategory() {
      const categorySelect = document.getElementById('category_id');
      const customCategoryDiv = document.getElementById('customCategoryDiv');
      const customCategoryInput = document.getElementById('custom_category');

      if (categorySelect.options[categorySelect.selectedIndex].text === 'Others') {
        customCategoryDiv.classList.remove('hidden');
        customCategoryInput.required = true;
      } else {
        customCategoryDiv.classList.add('hidden');
        customCategoryInput.required = false;
        customCategoryInput.value = '';
      }
    }

    function showRejectionReasonModal(reason) {
        document.getElementById('rejectionReasonContent').textContent = reason;
        document.getElementById('rejectionReasonModal').classList.remove('hidden');
        document.getElementById('rejectionReasonModal').classList.add('flex');
    }

    function closeRejectionReasonModal() {
        document.getElementById('rejectionReasonModal').classList.add('hidden');
        document.getElementById('rejectionReasonModal').classList.remove('flex');
    }

    function showNotificationModal(title, message, type = 'info') {
      const modal = document.getElementById('notificationModal');
      const titleEl = document.getElementById('notificationTitle');
      const messageEl = document.getElementById('notificationMessage');
      const iconEl = document.getElementById('notificationIcon');

      titleEl.textContent = title;
      messageEl.textContent = message;

      let iconHtml = '';
      if (type === 'success') {
        iconHtml = '<svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
      } else if (type === 'error') {
        iconHtml = '<svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
      } else {
        iconHtml = '<svg class="w-12 h-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
      }
      iconEl.innerHTML = iconHtml;

      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeNotificationModal() {
      document.getElementById('notificationModal').classList.add('hidden');
      document.getElementById('notificationModal').classList.remove('flex');
    }

    document.addEventListener('DOMContentLoaded', function() {
      @if(session('success'))
        showNotificationModal('Success', '{{ session('success') }}', 'success');
      @endif
      @if(session('error'))
        showNotificationModal('Error', '{{ session('error') }}', 'error');
      @endif
    });
  </script>

  <!-- Notification Modal -->
  <div id="notificationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[90] px-4">
    <div class="bg-white p-8 rounded-2xl w-full max-w-md shadow-2xl relative">
      <button onclick="closeNotificationModal()"
              class="absolute top-4 right-6 text-gray-400 hover:text-black text-3xl font-bold transition">
        &times;
      </button>

      <div class="text-center">
        <div id="notificationIcon" class="mb-4"></div>
        <h2 id="notificationTitle" class="text-2xl font-semibold mb-2">Notification</h2>
        <p id="notificationMessage" class="text-gray-700 mb-6">Message here...</p>
        <button onclick="closeNotificationModal()"
                class="px-6 py-2 bg-[#D5451B] text-white rounded hover:bg-[#aa3715] transition">
          OK
        </button>
      </div>
    </div>
  </div>
</body>
</html>
