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
  <div class="min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md p-6 space-y-4">
        <h2 class="text-xl font-semibold mb-4">Admin Panel</h2>
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
            <button id="btn-analytics" onclick="showPanel('analytics')" class="nav-btn block w-full text-left px-4 py-2 rounded hover:bg-[#E17C5F]">Site Analytics</button>
            <button id="btn-notifications" onclick="showPanel('notifications')" class="nav-btn block w-full text-left px-4 py-2 rounded hover:bg-[#E17C5F]">Notifications</button>
            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-btn block w-full text-left px-4 py-2 rounded text-red-500 hover:bg-red-100">Logout</button>
            </form>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8">
        <!-- Dashboard Panel -->
        <section id="dashboard" class="panel">
            <h1 class="text-2xl font-bold mb-6">Website Statistics</h1>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded shadow">
                    <h2 class="text-lg font-semibold">Total Students</h2>
                    <p class="text-3xl font-bold">1,248</p>
                </div>
                <div class="bg-white p-6 rounded shadow">
                    <h2 class="text-lg font-semibold">Total Facultys</h2>
                    <p class="text-3xl font-bold">1,248</p>
                </div>
                
                <div class="bg-white p-6 rounded shadow">
                    <h2 class="text-lg font-semibold">Guest Visitors</h2>
                    <p class="text-3xl font-bold">532</p>
                </div>
                <div class="bg-white p-6 rounded shadow">
                    <h2 class="text-lg font-semibold">Total Posts</h2>
                    <p class="text-3xl font-bold">87</p>
                </div>
                <div class="bg-white p-6 rounded shadow">
                    <h2 class="text-lg font-semibold">Total Departments</h2>
                    <p class="text-3xl font-bold">6</p>
                </div>
            </div>
        </section>

            <!-- Pending Approvals -->
        <section id="pending" class="panel hidden">
            <h1 class="text-2xl font-bold mb-6">Pending Announcements</h1>
            <p class="text-gray-700 mb-4">Review and approve announcements submitted by moderators.</p>
            <!-- Sample list of posts -->
            <div class="space-y-4">
                <div class="bg-white border rounded p-4">
                    <h2 class="font-semibold text-lg">Upcoming Enrollment</h2>
                    <p class="text-sm text-gray-700">Submitted by: Registrar</p>
                    <p class="my-2">Please be informed that enrollment starts on...</p>
                    <div class="flex gap-2 mt-2">
                        <button class="bg-green-600 text-white px-4 py-1 rounded">Approve</button>
                        <button class="bg-red-500 text-white px-4 py-1 rounded">Reject</button>
                    </div>
                </div>
            </div>
        </section>

            <!-- Faculty Announcement Post Panel -->
        <section id="postPanel" class="panel bg-white shadow-md rounded-xl p-6 max-w-3xl mx-auto my-10 hidden">
            <h1 class="text-2xl font-bold mb-6 text-[#521C0D]">Create Faculty Announcement</h1>

            <form action="{{ route('announcement.store') }}" method="POST" class="space-y-5">
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
                    <input type="checkbox" name="is_approved" id="is_approved" class="rounded border-gray-300 text-[#FF9B45] focus:ring-[#FF9B45]">
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


        <!-- All Posts -->
        <section id="allPosts" class="panel hidden">
            <h1 class="text-2xl font-bold mb-6">All Announcements</h1>
            <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <input type="text" placeholder="Search posts..." class="w-full sm:w-1/2 p-2 border border-gray-300 rounded">
                <select class="p-2 border border-gray-300 rounded">
                    <option>All Sources</option>
                    <option>Registrar</option>
                    <option>USG</option>
                    <option>Approved</option>
                    <option>Pending</option>
                    <option>Rejected</option>
                </select>
            </div>
                <div class="bg-white rounded shadow overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Title</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Source</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Date</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Posted By</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4"><span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded">Approved</span></td>
                        <td class="px-6 py-4">Enrollment Updates</td>
                        <td class="px-6 py-4">Registrar</td>
                        <td class="px-6 py-4">2025-07-05</td>
                        <td class="px-6 py-4">admin@school.edu</td>
                        <td class="px-6 py-4 space-x-2">
                        <button class="text-blue-600 hover:underline">View</button>
                        <button class="text-yellow-600 hover:underline">Edit</button>
                        <button class="text-red-600 hover:underline">Delete</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Moderators Panel -->
        <section id="moderators" class="panel hidden">
            <h1 class="text-2xl font-bold mb-6">Manage Moderators</h1>
            <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <input type="text" placeholder="Search moderators..." class="w-full sm:w-1/2 p-2 border border-gray-300 rounded">
            <button class="px-4 py-2 bg-[#E17C5F] text-white rounded">Add Moderator</button>
            </div>
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
                                    <a href="{{ route('students.show', $mod->id) }}" class="text-blue-600 hover:underline">View</a>

                                    <!-- Toggle Status -->
                                    <form method="POST" action="{{ route('students.toggleStatus', $mod->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="{{ $mod->status === 'active' ? 'text-yellow-600' : 'text-green-600' }} hover:underline">
                                            {{ $mod->status === 'active' ? 'Disable' : 'Activate' }}
                                        </button>
                                    </form>

                                    <!-- Delete -->
                                    <form method="POST" action="{{ route('students.destroy', $mod->id) }}" class="inline" onsubmit="return confirm('Are you sure?')">
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
            </div>
        </section>

    <!-- Manage Students Panel -->
    <section id="students" class="panel hidden">
        <h1 class="text-2xl font-bold mb-6">Manage Students</h1>
        <p class="text-gray-700 mb-4">Manage student user accounts and their course enrollments.</p>

        <!-- Toolbar -->
        <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <input type="text" placeholder="Search students..." class="w-full sm:w-1/2 p-2 border border-gray-300 rounded">
            <div class="flex gap-2">
                <button class="px-4 py-2 bg-[#E17C5F] text-white rounded">Add Student</button>
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
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Course</th>  <!-- NEW -->
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th>     <!-- NEW -->
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Role</th>       <!-- NEW -->
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Created</th>    <!-- NEW -->
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Actions</th>    <!-- LAST -->
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
                                <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
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

            @if (session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

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
        <p class="text-gray-700 mb-4">Manage faculty user accounts and their department affiliations.</p>

        <!-- Search + Action Buttons -->
        <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <input type="text" placeholder="Search faculty..." class="w-full sm:w-1/2 p-2 border border-gray-300 rounded">
            <div class="flex gap-2">
                <button onclick="toggleImportFacultyForm()" class="px-4 py-2 bg-[#E17C5F] text-white rounded">Import Faculty</button>
                <button class="px-4 py-2 bg-[#E17C5F] text-white rounded">Add Faculty</button>
            </div>
        </div>
                
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

                        <!-- Status -->
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded {{ $prof->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($prof->status ?? 'inactive') }}
                            </span>
                        </td>

                        <!-- Created At -->
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $prof->created_at ? $prof->created_at->diffForHumans() : 'N/A' }}
                        </td>

                        <!-- Actions -->
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
                            <form method="POST" action="{{ route('students.destroy', $prof->id) }}" class="inline" onsubmit="return confirm('Delete this faculty?')">
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
            @if (session('faculty_success'))
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                    {{ session('faculty_success') }}
                </div>
            @endif
            <form action="{{ route('admin.import.faculty') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium">Choose Excel File</label>
                    <input type="file" name="excelFile" accept=".xlsx" class="mt-2 w-full p-2 border border-gray-300 rounded" required>
                </div>
                <button type="submit" class="px-4 py-2 bg-[#E17C5F] text-white rounded hover:bg-[#c05e4d]">Upload and Import</button>
            </form>
            <p class="text-sm text-gray-500 mt-4">Faculty name, email, and password will be based on the email prefix. Department will be detected (e.g., BSIT, BSED).</p>
        </div>
    </section>


        <!-- Admin Settings Panel -->
        <section id="settings" class="panel hidden">
            <h1 class="text-2xl font-bold mb-6">Admin Settings</h1>
            <form class="space-y-6 max-w-xl">
                <div>
                    <label class="block text-sm font-medium mb-1">Change Email</label>
                    <input type="email" placeholder="admin@example.com" class="w-full p-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Change Password</label>
                    <input type="password" placeholder="New password" class="w-full p-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Confirm Password</label>
                    <input type="password" placeholder="Confirm new password" class="w-full p-2 border border-gray-300 rounded">
                </div>
                <button class="px-4 py-2 bg-[#E17C5F] text-white rounded hover:bg-[#c05e4d]">Save Settings</button>
            </form>
        </section>

            <!-- Activity Logs Panel -->
        <section id="activityLogs" class="panel hidden">
            <h1 class="text-2xl font-bold mb-6">Activity Logs</h1>
            <p class="text-gray-700 mb-4">Track recent activities and system changes performed by users and moderators.</p>
            <div class="bg-white shadow rounded overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Timestamp</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">User</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Role</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Action</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4">2025-07-05 09:42</td>
                        <td class="px-6 py-4">John Doe</td>
                        <td class="px-6 py-4">Registrar</td>
                        <td class="px-6 py-4">Submitted new announcement</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4">2025-07-05 10:03</td>
                        <td class="px-6 py-4">Admin</td>
                        <td class="px-6 py-4">Admin</td>
                        <td class="px-6 py-4">Approved announcement "Enrollment Week"</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4">2025-07-05 10:20</td>
                        <td class="px-6 py-4">Mary Jane</td>
                        <td class="px-6 py-4">USG</td>
                        <td class="px-6 py-4">Edited post "Welcome Event"</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Site Analytics Panel -->
        <section id="analytics" class="panel hidden">
            <h1 class="text-2xl font-bold mb-6">Site Analytics</h1>
            <p class="text-gray-700 mb-4">Visual overview of website traffic and user engagement.</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white p-6 rounded shadow">
                <h2 class="text-lg font-semibold">Daily Visitors</h2>
                <p class="text-3xl font-bold">325</p>
                </div>
                <div class="bg-white p-6 rounded shadow">
                <h2 class="text-lg font-semibold">Total Page Views</h2>
                <p class="text-3xl font-bold">7,480</p>
                </div>
                <div class="bg-white p-6 rounded shadow">
                <h2 class="text-lg font-semibold">Average Time on Site</h2>
                <p class="text-3xl font-bold">03:21</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <h2 class="text-lg font-semibold mb-4">Traffic Overview (Last 7 Days)</h2>
                <div class="w-full h-64 bg-gray-100 flex items-center justify-center text-gray-400">
                [Chart Placeholder]
                </div>
            </div>
        </section>

    <!-- Notifications Panel -->
    <section id="notifications" class="panel hidden">
        <h1  class="text-2xl font-bold mb-6">Notifications</h1>
        <p class="text-gray-700 mb-4">Customize how you receive important alerts and system messages.</p>

        <form class="bg-white rounded shadow p-6 space-y-6 max-w-xl">
            <!-- Notification Group -->
            <div class="flex justify-between items-center">
            <div>
                <h3 class="text-sm font-medium text-gray-800">New Announcement Approvals</h3>
                <p class="text-sm text-gray-500">Get notified when a moderator submits a post for review.</p>
            </div>
            <label class="inline-flex items-center cursor-pointer">
                <input type="checkbox" id="notif-approval" class="sr-only">
                <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-[#E17C5F] transition-all relative">
                    <div class="w-5 h-5 bg-white rounded-full absolute top-0.5 left-0.5 peer-checked:left-5 transition-all"></div>
                </div>
            </label>
            </div>

            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-medium text-gray-800">System Alerts</h3>
                    <p class="text-sm text-gray-500">Be alerted if any system warnings or errors occur.</p>
                </div>
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="notif-errors" class="sr-only">
                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-[#E17C5F] transition-all relative">
                    <div class="w-5 h-5 bg-white rounded-full absolute top-0.5 left-0.5 peer-checked:left-5 transition-all"></div>
                    </div>
                </label>
            </div>

            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-medium text-gray-800">Moderator Activity Logs</h3>
                    <p class="text-sm text-gray-500">Receive summaries of moderator actions and submissions.</p>
                </div>
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="notif-activity" class="sr-only">
                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-[#E17C5F] transition-all relative">
                    <div class="w-5 h-5 bg-white rounded-full absolute top-0.5 left-0.5 peer-checked:left-5 transition-all"></div>
                    </div>
                </label>
            </div>

            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-medium text-gray-800">Weekly Summary</h3>
                    <p class="text-sm text-gray-500">A digest report of recent activity and stats every Monday.</p>
                </div>
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="notif-weekly" class="sr-only">
                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-[#E17C5F] transition-all relative">
                    <div class="w-5 h-5 bg-white rounded-full absolute top-0.5 left-0.5 peer-checked:left-5 transition-all"></div>
                    </div>
                </label>
            </div>

            <div class="text-right">
                <button type="submit" class="mt-6 px-6 py-2 bg-[#E17C5F] text-white rounded hover:bg-[#c05e4d]">
                    Save Preferences
                </button>
            </div>
        </form>
    </section>

        <!-- All Posts -->
        <section id="allPosts" class="panel hidden">
            <h1 class="text-2xl font-bold mb-6">All Announcements</h1>
            <p class="text-gray-700">Full list of published announcements from all moderators.</p>
        </section>

        <!-- Moderators -->
        <section id="moderators" class="panel hidden">
            <h1 class="text-2xl font-bold mb-6">Moderator Accounts</h1>
            <p class="text-gray-700">View and manage all registered moderators (Registrar & USG).</p>
        </section>

        <section id="settings" class="panel hidden">
            <h1 class="text-2xl font-bold mb-6">Admin Settings</h1>
            <p class="text-gray-700 mb-4">Update your profile, change password, and personalize the dashboard.</p>
        </section>

        <section id="activityLogs" class="panel hidden">
            <h1 class="text-2xl font-bold mb-6">Activity Logs</h1>
            <p class="text-gray-700 mb-4">Track admin and moderator actions taken across the platform.</p>
        </section>

        <section id="analytics" class="panel hidden">
            <h1 class="text-2xl font-bold mb-6">Site Analytics</h1>
            <p class="text-gray-700 mb-4">View trends, traffic stats, and post performance.</p>
        </section>

        <section id="notifications" class="panel hidden">
            <h1 class="text-2xl font-bold mb-6">Notifications</h1>
            <p class="text-gray-700 mb-4">New announcements, account registrations, and platform alerts.</p>
        </section>

        <section id="students" class="panel hidden">
        <h1 class="text-2xl font-bold mb-6">Manage Students</h1>
        <p class="text-gray-700">View or edit registered student accounts.</p>
        </section>

        <section id="faculty" class="panel hidden">
            <h1 class="text-2xl font-bold mb-6">Manage Faculty Accounts</h1>
            <p class="text-gray-700">Add, edit, or remove faculty users and their permissions.</p>
        </section>

        <section id="logout" class="panel hidden">
            <h1 class="text-xl font-bold text-red-600">You have logged out successfully.</h1>
        </section>
    </main>
</div>
        <script src="//unpkg.com/alpinejs" defer></script>
        <script src="{{ asset('js/admin.js') }}"></script>
</body>
</html>
