<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="icon" href="{{ asset('logo/logo.ico') }}" type="image/png">
</head>

<body class="bg-[#F4E7E1] font-poppins">
    
    <div class="min-h-screen flex flex-col md:flex-row bg-[url('{{ asset('image/bg_registrar1.jpeg') }}')] bg-cover bg-center bg-no-repeat relative">
    <div class="fixed inset-0 bg-white/85 z-0 h-full min-h-screen"></div>
    <!-- Mobile Toggle Button -->
    <button id="toggleSidebar" class="md:hidden fixed top-4 right-4 z-50 p-2 bg-white rounded shadow">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <!-- Sidebar -->
    <aside id="sidebar" class="w-full md:w-64 bg-white shadow-md p-6 space-y-4 z-40 fixed md:static top-0 left-0 h-full md:h-auto transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
        <div class="flex items-center gap-4 mb-6">
            <a href="/" class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-gray-200 hover:bg-gray-300" title="Back to Home">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="text-xl font-semibold">Admin Panel</h2>
        </div>
        <nav class="space-y-2">
            <button id="btn-dashboard" onclick="showPanel('dashboard')" class="nav-btn block w-full text-left px-4 py-2 rounded hover:bg-[#E17C5F] bg-[#E17C5F] text-white">Dashboard</button>
            <button id="btn-postPanel" onclick="showPanel('postPanel')" class="nav-btn block w-full text-left px-4 py-2 rounded hover:bg-[#E17C5F]">Create Post</button>
            <button id="btn-pending" onclick="showPanel('pending')" class="nav-btn block w-full text-left px-4 py-2 rounded hover:bg-[#E17C5F]">Pending Approvals</button>
            <button id="btn-allPosts" onclick="showPanel('allPosts')" class="nav-btn block w-full text-left px-4 py-2 rounded hover:bg-[#E17C5F]">All Posts</button>
            <button id="btn-moderators" onclick="showPanel('moderators')" class="nav-btn block w-full text-left px-4 py-2 rounded hover:bg-[#E17C5F]">Moderators</button>
            <button id="btn-students" onclick="showPanel('students')" class="nav-btn block w-full text-left px-4 py-2 rounded hover:bg-[#E17C5F]">Manage Students</button>
            <button id="btn-faculty" onclick="showPanel('faculty')" class="nav-btn block w-full text-left px-4 py-2 rounded hover:bg-[#E17C5F]">Faculty Accounts</button>
            <button id="btn-settings" onclick="showPanel('settings')" class="nav-btn block w-full text-left px-4 py-2 rounded hover:bg-[#E17C5F]">Admin Settings</button>
            <button id="btn-activityLogs" onclick="showPanel('activityLogs')" class="nav-btn block w-full text-left px-4 py-2 rounded hover:bg-[#E17C5F]">Activity Logs</button>
            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-btn block w-full text-left px-4 py-2 rounded text-red-500 hover:bg-red-100">Logout</button>
            </form>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-4 sm:p-6 md:p-8 z-10 overflow-x-hidden">
        @if(session('success'))
            <div id="alertSuccess" class="transition-opacity duration-1000 opacity-100 bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div id="alertError" class="transition-opacity duration-1000 opacity-100 bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center hidden z-50">
            <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-lg">
                <h2 class="text-xl font-bold text-red-600 mb-4">Confirm Deletion</h2>
                <p class="text-gray-700 mb-6">Are you sure you want to delete <span id="deleteTargetName" class="font-semibold"></span>?</p>

                <div class="flex justify-end gap-4">
                    <button onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-300 text-black rounded hover:bg-gray-400">Cancel</button>
                    <button onclick="submitDeleteForm()" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
                </div>
            </div>
        </div>

        <!-- Dashboard Panel -->
        <section id="dashboard" class="panel">
            <h1 class="text-2xl font-bold mb-6">Website Statistics</h1>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded shadow">
                    <h2 class="text-lg font-semibold">Total Students</h2>
                    <p class="text-3xl font-bold">{{ $totalStudents }}</p>
                </div>
                <div class="bg-white p-6 rounded shadow">
                    <h2 class="text-lg font-semibold">Total Faculty</h2>
                    <p class="text-3xl font-bold">{{ $totalFaculty }}</p>
                </div>
                <div class="bg-white p-6 rounded shadow">
                    <h2 class="text-lg font-semibold">Total Posts</h2>
                    <p class="text-3xl font-bold">{{ $totalPosts }}</p>
                </div>
                <div class="bg-white p-6 rounded shadow">
                    <h2 class="text-lg font-semibold">Total Departments</h2>
                    <p class="text-3xl font-bold">{{ $totalDepartments }}</p>
                </div>
                <!-- ✅ New: Total Moderators -->
                <div class="bg-white p-6 rounded shadow">
                    <h2 class="text-lg font-semibold">Total Moderators</h2>
                    <p class="text-3xl font-bold">{{ $moderators->count() }}</p>
                </div>
            </div>
        </section>

    <!-- Pending Announcements Panel -->
    <section id="pending" class="panel hidden">
        <h1 class="text-2xl font-bold mb-6">Pending Announcements</h1>

        <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <input type="text" id="pendingSearch" placeholder="Search announcements..." 
            class="w-full sm:w-1/2 p-2 border border-gray-300 rounded" onkeyup="filterTableRows('pendingSearch', '#pending table tbody tr')">
        </div>

        <p class="text-gray-700 mb-4">Review and approve announcements submitted by moderators.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($pendingAnnouncements as $announcement)
            <div class="bg-white border rounded p-4 shadow-sm">
                @if($announcement->poster_image)
                    <img src="{{ asset('storage/' . $announcement->poster_image) }}"
                        alt="Poster Image"
                        class="w-full h-48 object-cover rounded mb-4">
                @endif

                <h2 class="font-semibold text-lg text-gray-800">{{ $announcement->title }}</h2>
                <p class="text-sm text-gray-600 mt-1">
                <strong>Submitted by:</strong> {{ $announcement->user->name ?? 'Unknown' }} <br>
                {{-- ({{ ucfirst($announcement->user->role ?? 'N/A') }})  --}}
                <strong>Submitted at:</strong> {{ $announcement->created_at->format('F j, Y g:i A') }}
                </p>

                <div class="my-3 text-gray-800 text-sm truncate max-w-full" style="max-width: 100%;">
                    {{ \Illuminate\Support\Str::limit($announcement->content, 300) }}
                </div>

                <div class="mt-2">
                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded 
                        @if($announcement->status === 'approved')
                        bg-green-100 text-green-800
                        @elseif($announcement->status === 'rejected')
                        bg-red-100 text-red-800
                        @else
                        bg-yellow-100 text-yellow-800
                        @endif">
                        {{ ucfirst($announcement->status) }}
                    </span>
                </div>

                @if ($announcement->status === 'pending')
                    <div class="flex gap-2 mt-4">
                        <!-- View Button -->
                        <button onclick="showAnnouncementModal({{ $announcement->id }})" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded">
                            View
                        </button>

                        <!-- Approve -->
                        <form action="{{ route('announcement.approve', $announcement->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-1 rounded">Approve</button>
                        </form>

                        <!-- Reject -->
                        <form action="{{ route('announcement.reject', $announcement->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded">Reject</button>
                        </form>
                    </div>
                @endif
            </div>
            @empty
            <p class="text-gray-500 col-span-full">No pending announcements available.</p>
            @endforelse
        </div>

        <!-- Announcement View Modal -->
        <div id="announcementModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 px-4">
            <div class="bg-white p-8 rounded-2xl w-full max-w-4xl shadow-2xl relative overflow-hidden">
                
                <!-- Close Button -->
                <button onclick="closeAnnouncementModal()" 
                        class="absolute top-4 right-6 text-gray-400 hover:text-black text-3xl font-bold transition">
                    &times;
                </button>

                <!-- Title -->
                <h2 id="modalTitle" 
                    class="text-2xl sm:text-3xl font-semibold text-gray-800 mb-4 leading-snug break-words">
                    Announcement Title
                </h2>

                <!-- Content Box -->
                <div class="max-h-[60vh] overflow-y-auto mb-6 pr-2">
                    <p id="modalContent" 
                    class="text-base sm:text-lg text-gray-700 whitespace-pre-line leading-relaxed break-words">
                        Full content goes here...
                    </p>
                </div>

                <!-- Footer Metadata -->
                <div class="text-sm text-gray-600 border-t pt-4 space-y-1">
                    <p><strong>Submitted at:</strong> <span id="modalCreatedAt"></span></p>
                    <p><strong>Submitted by:</strong> <span id="modalSubmittedBy"></span></p>
                </div>
            </div>
        </div>
        <script>
            const announcements = @json($pendingAnnouncements);

            function showAnnouncementModal(id) {
                const announcement = announcements.find(a => a.id === id);
                if (!announcement) return;

                document.getElementById('modalTitle').textContent = announcement.title;
                document.getElementById('modalContent').textContent = announcement.content;
                document.getElementById('modalCreatedAt').textContent = new Date(announcement.created_at).toLocaleString();
                document.getElementById('modalSubmittedBy').textContent = announcement.user?.name ?? 'Unknown';

                document.getElementById('announcementModal').classList.remove('hidden');
                document.getElementById('announcementModal').classList.add('flex');
            }

            function closeAnnouncementModal() {
                document.getElementById('announcementModal').classList.add('hidden');
            }
        </script>
    </section>

        <!--  Announcement Post Panel -->
        <section id="postPanel" class="panel bg-white shadow-md rounded-xl p-6 max-w-3xl mx-auto my-10 hidden">
            <h1 class="text-2xl font-bold mb-6 text-[#000000]">Create Announcement</h1>

            <form action="{{ route('announcement.admin.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input type="text" name="title" id="title" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#FF9B45]" placeholder="Enter announcement title" required>
                </div>
                <!-- Content -->
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                    <textarea name="content" id="content" rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#FF9B45]" placeholder="Enter announcement content..." required></textarea>
                </div>
                <!-- Poster Image -->
                <div>
                    <label for="poster_image" class="block text-sm font-medium text-gray-700 mb-1">Poster Image (Optional)</label>
                    <input type="file" name="poster_image" id="poster_image"
                        accept="image/*"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#FF9B45]">
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="category_id" id="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#FF9B45]" required>
                        <option value="">-- Select Category --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Approval -->
                <div class="flex items-center space-x-2">
                    <input type="checkbox" name="is_approved" id="is_approved" class="rounded border-gray-300 text-[#FF9B45] focus:ring-[#FF9B45]" required>
                    <label for="is_approved" class="text-sm text-gray-700">Mark as Approved</label>
                </div>
                <!-- Submit -->
                <div class="pt-4">
                    <button type="submit" class="w-full sm:w-auto bg-[#F15B24] hover:bg-[#FF9B45] text-white font-semibold px-6 py-2 rounded-md shadow-sm transition">
                        Post Announcement
                    </button>
                </div>
            </form>
        </section>

        <!-- All Posts Panel -->
        <section id="allPosts" class="panel hidden">
            <h1 class="text-2xl font-bold mb-6">All Announcements</h1>
            <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <input type="text" id="allPostsSearch" placeholder="Search moderators..." 
                class="w-full sm:w-1/2 p-2 border border-gray-300 rounded" onkeyup="filterTableRows('allPostsSearch', '#allPosts table tbody tr')">
            </div>
            <div class="bg-white rounded shadow overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Poster</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Title</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Content</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Date</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Posted By</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($announcements as $post)
                        <tr>
                            <!-- Status -->
                            <td class="px-6 py-4">
                                @if ($post->status === 'approved')
                                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded">Approved</span>
                                @elseif ($post->status === 'rejected')
                                    <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded">Rejected</span>
                                @else
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded">Pending</span>
                                @endif
                            </td>

                            <!-- Poster Image -->
                            <td class="px-6 py-4">
                                @if($post->poster_image)
                                    <img src="{{ asset('storage/' . $post->poster_image) }}"
                                        alt="Poster Image"
                                        class="w-24 h-24 object-cover rounded shadow">
                                @else
                                    <span class="text-gray-400 italic text-sm">No image</span>
                                @endif
                            </td>

                            <!-- Title -->
                            <td class="px-6 py-4 font-semibold">
                                {{ $post->title }}
                            </td>

                            <!-- Content -->
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ Str::limit(strip_tags($post->content), 80) }}
                            </td>

                            <!-- Date -->
                            <td class="px-6 py-4">
                                {{ $post->created_at->format('Y-m-d') }}
                            </td>

                            <!-- Posted By -->
                            <td class="px-6 py-4">
                                {{ $post->user->name ?? 'Unknown' }}
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 space-x-2">
                                <a href="{{ route('announcements.show', $post->id) }}" class="text-blue-600 hover:underline">View</a>
                                <a href="{{ route('announcements.edit', $post->id) }}" class="text-yellow-600 hover:underline">Edit</a>
                                <form method="POST" action="{{ route('announcements.destroy', $post->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this announcement?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline" title="Delete Announcement">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No announcements found.</td>
                        </tr>
                    @endforelse
                </tbody>

                </table>
            </div>
        </section>

        <!-- Moderators Panel -->
        <section id="moderators" class="panel hidden">
            <h1 class="text-2xl font-bold mb-6">Manage Moderators</h1>
            <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <input type="text" id="moderatorSearch" placeholder="Search moderators..." 
                class="w-full sm:w-1/2 p-2 border border-gray-300 rounded" onkeyup="filterTableRows('moderatorSearch', '#moderators table tbody tr')">
                <!-- Add Moderator Button -->
                <button id="addModeratorBtn" type="button" class="px-4 py-2 bg-[#E17C5F] text-white rounded">
                    Add Moderator
                </button>
            </div>
                <!-- Add Moderator Modal -->
                <div id="addModeratorModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
                    <div class="bg-white p-8 rounded-lg w-full max-w-2xl shadow-lg">
                        @if (session('success'))
                        <div class="p-3 bg-green-100 text-green-800 rounded mb-4">
                            {{ session('success') }}
                        </div>
                        @endif
                        <h2 class="text-2xl font-bold mb-6 text-center">Add Moderator</h2>
                        <form action="{{ route('admin.moderators.store') }}" method="POST">
                            @csrf
                            <!-- Name -->
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                <input type="text" name="name" id="name" required
                                    class="w-full mt-1 p-2 border border-gray-300 rounded">
                            </div>
                            <!-- Email -->
                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" required
                                    class="w-full mt-1 p-2 border border-gray-300 rounded">
                            </div>
                            <!-- Password -->
                            <div class="mb-4">
                                <label for="password" class="block text-sm font-medium text-gray-700">Temporary Password</label>
                                <input type="password" name="password" id="password" required
                                    class="w-full mt-1 p-2 border border-gray-300 rounded">
                            </div>
                            <!-- Role -->
                            <div class="mb-4">
                                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                                <select name="role" id="role" required
                                    class="w-full mt-1 p-2 border border-gray-300 rounded">
                                    <option value="">Select Role</option>
                                    @foreach ($availableRoles as $role)
                                        <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Buttons -->
                            <div class="flex justify-end gap-4">
                                <button type="button" onclick="toggleAddModeratorModal()"
                                    class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 bg-[#E17C5F] text-white rounded hover:bg-[#c05e4d]">
                                    Create
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            <!-- Moderators Table -->
            <div class="bg-white rounded shadow overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Name</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Email</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Role</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($moderators as $mod)
                            <tr>
                                <td class="px-6 py-4">{{ $mod->name }}</td>
                                <td class="px-6 py-4">{{ $mod->email }}</td>
                                <td class="px-6 py-4 capitalize">{{ $mod->role }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded
                                        {{ $mod->status === 'active' ? 'bg-green-100 text-green-700' :
                                        ($mod->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                        {{ ucfirst($mod->status ?? 'inactive') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 space-x-2">
                                    <!-- View -->
                                    <a href="{{ route('moderators.show', $mod->id) }}" class="text-blue-600 hover:underline">View</a>
                                    <!-- Toggle Status -->
                                    <form method="POST" action="{{ route('students.toggleStatus', $mod->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="{{ $mod->status === 'active' ? 'text-yellow-600' : 'text-green-600' }} hover:underline">
                                            {{ $mod->status === 'active' ? 'Disable' : 'Activate' }}
                                        </button>
                                    </form>
                                    <!-- Delete -->
                                <form method="POST"
                                    action="{{ route('accounts.destroy', $mod->id) }}"
                                    class="inline delete-form"
                                    data-name="{{ $mod->name }}"
                                    data-panel="moderators"
                                    onsubmit="return showDeleteModal(event)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No moderators found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if(session('success'))
                    <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif
            </div>

            <!-- Modal Script -->
            <script>
                function toggleAddModeratorModal() {
                    const modal = document.getElementById('addModeratorModal');
                    modal.classList.toggle('hidden');
                }

                document.addEventListener('DOMContentLoaded', function () {
                    document.getElementById('addModeratorBtn').addEventListener('click', toggleAddModeratorModal);
                });
            </script>
        </section>


    <!-- Manage Students Panel -->
    <section id="students" class="panel hidden">
        <h1 class="text-2xl font-bold mb-6">Manage Students</h1>
        <!-- Toolbar -->
        <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <input type="text" id="studentSearch" placeholder="Search students..." 
            class="w-full sm:w-1/2 p-2 border border-gray-300 rounded" onkeyup="filterTableRows('studentSearch', '#students table tbody tr')">
            <div class="flex gap-2">
                {{-- <button class="px-4 py-2 bg-[#E17C5F] text-white rounded">Add Student</button> --}}
                <button onclick="toggleImportForm()" class="px-4 py-2 bg-[#E17C5F] text-white rounded">Import Students</button>
            </div>
        </div>

            <!-- Students Table -->
            <div class="bg-white rounded shadow overflow-x-auto mb-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Name</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Email</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Course</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Role</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Created</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($students as $student)
                        <tr>
                            <td class="px-6 py-4">{{ $student->name }}</td>
                            <td class="px-6 py-4">{{ $student->email }}</td>
                            <td class="px-6 py-4">{{ $student->department->name ?? 'N/A' }}</td>

                            <td class="px-6 py-4">
                                @if ($student->status === 'active')
                                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded">Active</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded">Disabled</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ ucfirst($student->role) }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $student->created_at ? $student->created_at->diffForHumans() : 'N/A' }}
                            </td>

                            <td class="px-6 py-4 space-x-2">
                                <!-- View -->
                                <a href="{{ route('students.show', $student->id) }}" class="text-blue-600 hover:underline">View</a>

                                <!-- Edit -->
                                <a href="{{ route('students.edit', $student->id) }}" class="text-yellow-600 hover:underline">Edit</a>

                                <!-- Activate/Disable -->
                                <form action="{{ route('students.toggleStatus', $student->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="{{ $student->status === 'active' ? 'text-yellow-600' : 'text-green-600' }} hover:underline">
                                        {{ $student->status === 'active' ? 'Disable' : 'Activate' }}
                                    </button>
                                </form>

                                <!-- Delete -->
                                <form method="POST"
                                    action="{{ route('accounts.destroy', $student->id) }}"
                                    class="inline delete-form"
                                    data-name="{{ $student->name }}"
                                    data-panel="students"
                                    onsubmit="return showDeleteModal(event)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No students found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            

        <!-- Import Students Form -->
        <div id="importStudentForm" class="bg-white p-6 rounded shadow max-w-xl hidden">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Import Students</h2>
                <button onclick="toggleImportForm()" class="text-gray-500 hover:text-gray-700 text-xl font-bold">✕</button>
            </div>

            <form action="{{ route('admin.import.students') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium">Choose Excel File</label>
                    <input type="file" name="excelFile" accept=".xlsx" class="mt-2 w-full p-2 border border-gray-300 rounded" required>
                </div>
                <button type="submit" class="px-4 py-2 bg-[#E17C5F] text-white rounded hover:bg-[#c05e4d]">Upload and Import</button>
            </form>

            <p class="text-sm text-gray-500 mt-4">Name and password will be based on the part before the "@". Department will be detected (e.g., BSIT, HM, BSED).</p>
        </div>
            
    </section>

        <!-- Faculty Accounts Panel -->
        <section id="faculty" class="panel hidden">
            <h1 class="text-2xl font-bold mb-6">Faculty Accounts</h1>

            <!-- Search + Action Buttons -->
            <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <input type="text" id="facultySearch" placeholder="Search faculty..." 
                class="w-full sm:w-1/2 p-2 border border-gray-300 rounded" onkeyup="filterTableRows('facultySearch', '#faculty table tbody tr')">
                <div class="flex gap-2">
                    <button onclick="toggleImportFacultyForm()" class="px-4 py-2 bg-[#E17C5F] text-white rounded">Import Faculty</button>
                    {{-- <button class="px-4 py-2 bg-[#E17C5F] text-white rounded">Add Faculty</button> --}}
                </div>
            </div>

            <!-- Session Success Message -->
            @if (session('faculty_success'))
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
                    {{ session('faculty_success') }}
                </div>
            @endif

            <!-- Faculty Table -->
            <div class="bg-white rounded shadow overflow-x-auto mb-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Name</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Email</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Department</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Created</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($faculty as $prof)
                            <tr>
                                <td class="px-6 py-4">{{ $prof->name }}</td>
                                <td class="px-6 py-4">{{ $prof->email }}</td>
                                <td class="px-6 py-4">{{ $prof->department->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded {{ $prof->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ ucfirst($prof->status ?? 'inactive') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $prof->created_at ? $prof->created_at->diffForHumans() : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 space-x-2">
                                    <a href="{{ route('faculty.show', $prof->id) }}" class="text-blue-600 hover:underline">View</a>

                                    <!-- Toggle Active/Inactive -->
                                    <form method="POST" action="{{ route('students.toggleStatus', $prof->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="{{ $prof->status === 'active' ? 'text-yellow-600' : 'text-green-600' }} hover:underline">
                                            {{ $prof->status === 'active' ? 'Disable' : 'Activate' }}
                                        </button>
                                    </form>

                                    <!-- Delete -->
                                    <form method="POST"
                                            action="{{ route('accounts.destroy', $prof->id) }}"
                                            class="inline delete-form"
                                            data-name="{{ $prof->name }}"
                                            data-panel="faculty"
                                            onsubmit="return showDeleteModal(event)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No faculty found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Import Faculty Form -->
            <div id="importFacultyForm" class="bg-white p-6 rounded shadow max-w-xl hidden mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Import Faculty</h2>
                    <button onclick="toggleImportFacultyForm()" class="text-gray-500 hover:text-gray-700 text-xl font-bold">✕</button>
                </div>
                <form action="{{ route('admin.import.faculty') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium">Choose Excel File</label>
                        <input type="file" name="excelFile" accept=".xlsx" class="mt-2 w-full p-2 border border-gray-300 rounded" required>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-[#E17C5F] text-white rounded hover:bg-[#c05e4d]">Upload and Import</button>
                </form>
                <p class="text-sm text-gray-500 mt-4">
                    Faculty name, email, and password will be based on the email prefix. Department will be detected (e.g., BSIT, BSED).
                </p>
            </div>
        </section>

    {{-- Admin settings --}}
    <section id="settings" class="panel hidden">
        <h1 class="text-2xl font-bold mb-6">Admin Settings</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl">
            <!-- LEFT: Profile Info -->
            <div class="bg-white p-6 rounded shadow border">
                <form method="POST" action="{{ route('user.update') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <h2 class="text-xl font-semibold mb-4">Update Profile</h2>

                    <!-- Profile Picture -->
                    <div class="flex items-center gap-6">
                        <img id="profile-preview"
                            src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTezfOC3OmGvUnyrEpDPez1zyz-ibH_Hn7ZEctdJs3-IohUKEpT3mA5XuVfBpjhuRw16ws' }}"
                            class="rounded-full w-24 h-24 object-cover border-2 border-gray-300 shadow" />

                        <input type="file" name="profile_picture" accept="image/*" onchange="previewProfileImage(this)" class="text-sm">
                    </div>
                    @error('profile_picture')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Name</label>
                        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" class="w-full p-2 border rounded" required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="w-full p-2 border rounded" required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-[#E17C5F] text-white rounded hover:bg-[#c05e4d]">Save Profile</button>
                    </div>

                    @if(session('success'))
                        <p class="text-green-600 text-sm mt-2">{{ session('success') }}</p>
                    @endif
                </form>
            </div>

            <!-- RIGHT: Change Password -->
            <div class="bg-white p-6 rounded shadow border">
                <form method="POST" action="{{ route('admin.updatePassword') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <h2 class="text-xl font-semibold mb-4">Change Password</h2>

                    <!-- Current Password -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Current Password</label>
                        <input type="password" name="current_password" class="w-full p-2 border border-gray-300 rounded" required>
                        @error('current_password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div>
                        <label class="block text-sm font-medium mb-1">New Password</label>
                        <input type="password" name="new_password" class="w-full p-2 border border-gray-300 rounded" required>
                        @error('new_password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Confirm Password</label>
                        <input type="password" name="new_password_confirmation" class="w-full p-2 border border-gray-300 rounded" required>
                    </div>

                    <!-- Submit -->
                    <div class="flex justify-end">
                        <button class="px-4 py-2 bg-[#E17C5F] text-white rounded hover:bg-[#c05e4d]">Save Password</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    {{-- Activity Panel --}}
    <section id="activityLogs" class="panel hidden">
        <h1 class="text-2xl font-bold mb-6">Activity Logs</h1>
            <p class="text-gray-700 mb-4">Track recent activities and system changes performed by users and moderators.</p>
            <div class="flex items-center justify-end gap-2 px-4 pb-4">
                {{-- <a href="{{ route('activityLogs.export', 'pdf') }}" target="_blank"
                    class="inline-block px-4 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition">
                    Download PDF
                </a>
                <a href="{{ route('activityLogs.export', 'docx') }}" target="_blank"
                    class="inline-block px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
                    Download DOCX
                </a> --}}
                <a href="{{ route('activityLogs.export', 'excel') }}" target="_blank"
                    class="inline-block px-4 py-2 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition">
                    Download Excel
                </a>
                <a href="{{ route('activityLogs.export', 'txt') }}" target="_blank"
                    class="inline-block px-4 py-2 bg-gray-600 text-white text-sm rounded hover:bg-gray-700 transition">
                    Download TXT
                </a>
            </div>
            <div class="bg-white shadow rounded overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Timestamp</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">User</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Action</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Related & Role</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-sm text-gray-700">
                        @forelse ($logs as $log)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-2 whitespace-nowrap text-gray-700">
                                    {{ \Carbon\Carbon::parse($log->timestamp)->timezone('Asia/Manila')->format('Y-m-d H:i') }}
                                </td>

                                <td class="px-6 py-2 font-medium text-gray-900">
                                    {{ $log->user->name ?? 'Guest' }}
                                    <span class="block text-xs text-gray-700">{{ ucfirst($log->user->role ?? 'guest') }}</span>
                                </td>

                                <td class="px-6 py-2">
                                    <span class="inline-block px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-medium">
                                        {{ ucfirst($log->action) }}
                                    </span>
                                </td>

                                <td class="px-6 py-2 text-gray-700">
                                    @php
                                        $target = $log->target;
                                    @endphp

                                    @if ($target)
                                        <div class="text-sm">
                                            <span class="font-semibold">{{ class_basename($log->target_type) }}</span>
                                            @if (optional($target)->title)
                                                — “{{ Str::limit($target->title, 30) }}”
                                            @elseif (optional($target)->name)
                                                — {{ $target->name }}
                                            @endif
                                        </div>
                                    @elseif (is_null($log->target_type))
                                        <span class="text-gray-700 italic">System event (login/logout)</span>
                                    @else
                                        <span class="text-gray-700 italic">No related record</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-2 text-center text-gray-500">
                                    No activity logs found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
    </section>

    </main>

</div>


        <script src="//unpkg.com/alpinejs" defer></script>
        <script src="{{ asset('js/admin.js') }}"></script>
</body>
</html>
