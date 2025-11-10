<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

<body class="bg-gradient-to-br from-slate-50 to-slate-100 font-sans min-h-screen overflow-hidden">

    <div class="h-screen flex flex-col md:flex-row bg-[url('{{ asset('image/bg_registrar1.jpeg') }}')] bg-cover bg-center bg-no-repeat relative">
        <div class="absolute inset-0 bg-white/90 backdrop-blur-sm z-0"></div>
        <!-- Mobile Toggle Button -->
        <button id="toggleSidebar" class="md:hidden fixed top-4 left-4 z-50 p-2 bg-white rounded-lg shadow-custom">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Sidebar -->
        <aside id="sidebar" class="w-full md:w-72 bg-white shadow-xl p-6 space-y-6 z-40 fixed md:static top-0 left-0 h-full md:h-screen overflow-y-auto transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
            <div class="flex items-center gap-4 mb-8">
                <a href="/" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-slate-200 hover:bg-slate-300 transition" title="Back to Home">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="text-xl font-bold text-slate-800">Admin Dashboard</h2>
            </div>
            <nav class="space-y-3">
                <button id="btn-dashboard" onclick="showPanel('dashboard')" class="nav-btn w-full text-left px-4 py-3 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 transition flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Dashboard
                </button>
                <button id="btn-postPanel" onclick="showPanel('postPanel')" class="nav-btn w-full text-left px-4 py-3 rounded-lg hover:bg-slate-200 transition flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create Post
                </button>
                <button id="btn-pending" onclick="showPanel('pending')" class="nav-btn w-full text-left px-4 py-3 rounded-lg hover:bg-slate-200 transition relative flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Pending Approvals
                    @if($pending > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center">
                            {{ $pending > 99 ? '99+' : $pending }}
                        </span>
                    @endif
                </button>
                <button id="btn-allPosts" onclick="showPanel('allPosts')" class="nav-btn w-full text-left px-4 py-3 rounded-lg hover:bg-slate-200 transition flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    All Posts
                </button>
                <button id="btn-moderators" onclick="showPanel('moderators')" class="nav-btn w-full text-left px-4 py-3 rounded-lg hover:bg-slate-200 transition flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                    Moderators
                </button>
                <button id="btn-students" onclick="showPanel('students')" class="nav-btn w-full text-left px-4 py-3 rounded-lg hover:bg-slate-200 transition flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Manage Students
                </button>
                <button id="btn-faculty" onclick="showPanel('faculty')" class="nav-btn w-full text-left px-4 py-3 rounded-lg hover:bg-slate-200 transition flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"/>
                    </svg>
                    Faculty Accounts
                </button>
                <button id="btn-password-requests" onclick="showPanel('password-requests')" class="nav-btn w-full text-left px-4 py-3 rounded-lg hover:bg-slate-200 transition relative flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                    Password Requests
                    @if(($pendingResetCount ?? 0) > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center">
                            {{ $pendingResetCount > 99 ? '99+' : $pendingResetCount }}
                        </span>
                    @endif
                </button>
                <button id="btn-settings" onclick="showPanel('settings')" class="nav-btn w-full text-left px-4 py-3 rounded-lg hover:bg-slate-200 transition flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Admin Settings
                </button>
                <button id="btn-activityLogs" onclick="showPanel('activityLogs')" class="nav-btn w-full text-left px-4 py-3 rounded-lg hover:bg-slate-200 transition flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Activity Logs
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
        <main class="flex-1 p-4 sm:p-6 md:p-8 z-10 h-screen overflow-y-auto overflow-x-hidden">
        @if(session('success'))
            <div id="alertSuccess" class="fixed inset-0 flex items-center justify-center z-50">
                <div class="bg-green-100 text-green-700 px-8 py-6 rounded-lg shadow-lg flex flex-col items-center animate-fade-in">
                    <div class="mb-2 text-lg font-semibold">Success</div>
                    <div class="mb-4">{{ session('success') }}</div>
                    <button onclick="closeAlert('alertSuccess')" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 focus:outline-none">OK</button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div id="alertError" class="fixed inset-0 flex items-center justify-center z-50">
                <div class="bg-red-100 text-red-700 px-8 py-6 rounded-lg shadow-lg flex flex-col items-center animate-fade-in">
                    <div class="mb-2 text-lg font-semibold">Error</div>
                    <div class="mb-4">{{ session('error') }}</div>
                    <button onclick="closeAlert('alertError')" class="px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700 focus:outline-none">OK</button>
                </div>
            </div>
        @endif

        <style>
        @keyframes fade-in {
            from { opacity: 0; transform: scale(0.95);}
            to { opacity: 1; transform: scale(1);}
        }
        .animate-fade-in {
            animation: fade-in 0.3s ease;
        }
        </style>
        <script>
        function closeAlert(id) {
            const el = document.getElementById(id);
            if (el) el.style.display = 'none';
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
        </script>

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
            <h1 class="text-3xl font-bold mb-8 text-slate-800">Website Statistics</h1>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-custom hover:shadow-lg transition flex items-center gap-4">
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-slate-600">Total Students</h2>
                        <p class="text-3xl font-bold text-slate-800">{{ $totalStudents }}</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-custom hover:shadow-lg transition flex items-center gap-4">
                    <div class="bg-green-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-slate-600">Total Faculty</h2>
                        <p class="text-3xl font-bold text-slate-800">{{ $totalFaculty }}</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-custom hover:shadow-lg transition flex items-center gap-4">
                    <div class="bg-purple-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-slate-600">Total Departments</h2>
                        <p class="text-3xl font-bold text-slate-800">{{ $totalDepartments }}</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-custom hover:shadow-lg transition flex items-center gap-4">
                    <div class="bg-orange-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-slate-600">Total Moderators</h2>
                        <p class="text-3xl font-bold text-slate-800">{{ $moderators->count() }}</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-custom hover:shadow-lg transition flex items-center gap-4">
                    <div class="bg-red-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12a9 9 0 1118 0A9 9 0 013 12zm3 0h12"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-slate-600">Guest Page Views</h2>
                        <p class="text-3xl font-bold text-slate-800">{{ number_format($guestPageViews) }}</p>
                    </div>
                </div>
            </div>

            <!-- Stats Section -->
            <section class="mt-10">
                <div class="bg-white p-8 rounded-xl shadow-custom max-w-4xl">
                    <h1 class="text-2xl font-bold mb-6 text-center text-slate-800">Announcement Statistics</h1>

                    <div class="overflow-hidden rounded-lg border border-slate-200">
                        <table class="w-full">
                            <thead class="bg-slate-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Metric</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Count</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                <tr>
                                    <td class="px-6 py-4 text-slate-800 font-medium">Total Announcements</td>
                                    <td class="px-6 py-4 text-slate-900 font-semibold">{{ $totalPosts }}</td>
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
                </div>
            </section>
        </section>


    <!-- Pending Announcements Panel -->
    <section id="pending" class="panel hidden">
        <h1 class="text-2xl font-bold mb-6">Pending Announcements</h1>

        <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <input type="text" id="pendingSearch" placeholder="Search announcements..."
            class="w-full sm:w-1/2 p-2 border border-gray-300 rounded" onkeyup="filterCards('pendingSearch', '#pending .grid > div')">
        </div>

        <p class="text-gray-700 mb-4">Review and approve announcements submitted by moderators.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($pendingAnnouncements as $announcement)
            <div class="bg-white border rounded p-4 shadow-sm">
                @if($announcement->poster_image)
                    @php
                        $posterRelative = str_contains($announcement->poster_image, '/')
                            ? ltrim($announcement->poster_image, '/')
                            : 'posters/' . $announcement->poster_image;
                        $ext = strtolower(pathinfo($posterRelative, PATHINFO_EXTENSION));
                    @endphp
                    @if(in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']))
                        <img src="{{ asset('storage/' . $posterRelative) }}"
                            alt="Poster Image"
                            class="w-full h-48 object-cover rounded mb-4">
                    @else
                        <a href="{{ asset('storage/' . $posterRelative) }}" target="_blank" class="block">
                            <img src="{{ asset('image/icon/pdf-(1).svg') }}" alt="File" class="w-full h-48 object-contain rounded mb-4">
                        </a>
                    @endif
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
                        <button onclick="showRejectModal({{ $announcement->id }})" class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded">Reject</button>
                    </div>
                @endif
            </div>
            @empty
            <p class="empty-message text-gray-500 col-span-full text-center">No pending announcements available.</p>
            @endforelse
        </div>

        <!-- Announcement View Modal -->
        <div id="announcementModal" class="fixed inset-0 bg-black bg-opacity-50 z-[60] px-4" style="display: none;">
            <div class="bg-white p-8 rounded-2xl w-full max-w-4xl shadow-2xl relative overflow-hidden max-h-[90vh] overflow-y-auto ml-[350px]">

                <!-- Close Button -->
                <button onclick="closeAnnouncementModal()"
                        class="absolute top-4 right-6 text-gray-400 hover:text-black text-3xl font-bold transition z-[70]">
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

        <!-- Rejection Modal -->
        <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 z-[70] px-4" style="display: none;">
            <div class="bg-white p-8 rounded-2xl w-full max-w-2xl shadow-2xl relative">
                <button onclick="closeRejectModal()"
                        class="absolute top-4 right-6 text-gray-400 hover:text-black text-3xl font-bold transition z-[80]">
                    &times;
                </button>

                <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Reject Announcement</h2>

                <form id="rejectForm" method="POST" action="{{ route('announcement.reject', ['id' => '__ID__']) }}">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="announcement_id" id="reject_announcement_id">

                    <div class="mb-6">
                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Reason for Rejection <span class="text-red-500">*</span>
                        </label>
                        <textarea name="rejection_reason" id="rejection_reason" rows="4"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                                placeholder="Please provide a reason for rejecting this announcement..."
                                required></textarea>
                    </div>

                    <div class="flex justify-end gap-4">
                        <button type="button" onclick="closeRejectModal()"
                                class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                            Reject Announcement
                        </button>
                    </div>
                </form>
            </div>
        </div>

            <script>
                // Initialize announcements array for pending announcements
                const announcements = [
                    @foreach($pendingAnnouncements as $announcement)
                    {
                        id: {{ $announcement->id }},
                        title: '{{ addslashes($announcement->title) }}',
                        content: '{{ addslashes($announcement->content) }}',
                        created_at: '{{ $announcement->created_at->format('Y-m-d H:i:s') }}',
                        user: {
                            name: '{{ addslashes($announcement->user->name ?? 'Unknown') }}'
                        }
                    }{{ !$loop->last ? ',' : '' }}
                    @endforeach
                ];

                // Show Panel Logic
                function showPanel(id) {
                    document.querySelectorAll('.panel').forEach(panel => panel.classList.add('hidden'));
                    const active = document.getElementById(id);
                    if (active) active.classList.remove('hidden');

                    document.querySelectorAll('.nav-btn').forEach(btn => btn.classList.remove('bg-[#E17C5F]', 'text-white'));
                    const clicked = document.querySelector(`#btn-${id}`);
                    if (clicked) clicked.classList.add('bg-[#E17C5F]', 'text-white');

                    if (window.innerWidth < 768) {
                        const sidebar = document.getElementById('sidebar');
                        sidebar?.classList.add('-translate-x-full');
                    }

                    history.replaceState(null, null, '#' + id);
                }

                // On Page Load
                document.addEventListener('DOMContentLoaded', function () {
                    const hash = window.location.hash.replace('#', '') || 'dashboard';
                    showPanel(hash);
                });

                function showAnnouncementModal(id) {
                    console.log('showAnnouncementModal called with id:', id);

                    // Close any open modals first
                    closeRejectModal();

                    const announcement = announcements.find(a => a.id === id);
                    console.log('Found announcement:', announcement);
                    if (!announcement) {
                        console.log('No announcement found with id:', id);
                        return;
                    }

                    document.getElementById('modalTitle').textContent = announcement.title;
                    document.getElementById('modalContent').textContent = announcement.content;
                    document.getElementById('modalCreatedAt').textContent = new Date(announcement.created_at).toLocaleString();
                    document.getElementById('modalSubmittedBy').textContent = announcement.user?.name ?? 'Unknown';

                    const modal = document.getElementById('announcementModal');
                    console.log('Modal element:', modal);
                    modal.style.display = 'flex';
                    modal.classList.add('items-center', 'justify-center');
                    console.log('Modal should now be visible');
                }

                function closeAnnouncementModal() {
                    const modal = document.getElementById('announcementModal');
                    modal.classList.remove('items-center', 'justify-center');
                    modal.style.display = 'none';
                }

                let currentRejectId = null;

                function showRejectModal(id) {
                    // Close any open modals first
                    closeAnnouncementModal();

                    currentRejectId = id;
                    document.getElementById('rejectForm').action = document.getElementById('rejectForm').action.replace('__ID__', id);
                    document.getElementById('reject_announcement_id').value = id;

                    const modal = document.getElementById('rejectModal');
                    modal.style.display = 'flex';
                    modal.classList.add('items-center', 'justify-center');
                    document.getElementById('rejection_reason').focus();
                }

                function closeRejectModal() {
                    const modal = document.getElementById('rejectModal');
                    modal.classList.remove('items-center', 'justify-center');
                    modal.style.display = 'none';
                }

                function toggleAdminCustomCategory() {
                    const categorySelect = document.getElementById('admin_category_id');
                    const customCategoryDiv = document.getElementById('admin_customCategoryDiv');
                    const customCategoryInput = document.getElementById('admin_custom_category');

                    if (categorySelect.options[categorySelect.selectedIndex].text === 'Others') {
                        customCategoryDiv.classList.remove('hidden');
                        customCategoryInput.required = true;
                    } else {
                        customCategoryDiv.classList.add('hidden');
                        customCategoryInput.required = false;
                        customCategoryInput.value = '';
                    }
                }

                function promptDeclineReason(button) {
                    const reason = prompt('Optional: provide a reason for declining this reset request.');
                    const form = button.closest('form');
                    if (!form) {
                        return;
                    }
                    if (reason === null) {
                        return; // admin cancelled
                    }
                    form.querySelector('input[name="reason"]').value = reason;
                    form.submit();
                }
            </script>

        </section>

        <!--  Announcement Post Panel -->
        <section id="postPanel" class="panel hidden">
            <div class="form-container bg-white shadow-md rounded-xl p-6 max-w-3xl mx-auto mb-4 overflow-y-auto">
                <h1 class="text-xl font-bold mb-3 text-[#000000]">Create Announcement</h1>

                <form action="{{ route('announcement.admin.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title" id="title" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#FF9B45]" placeholder="Enter announcement title" required>
                    </div>
                    <!-- Content -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                        <textarea name="content" id="content" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#FF9B45]" placeholder="Enter announcement content..." required></textarea>
                    </div>
                    <!-- Poster Image (single image or PDF, optional) -->
                    <div>
                        <label for="poster_image" class="block text-sm font-medium text-gray-700 mb-1">Poster Image or PDF (optional)</label>
                        <input type="file" name="poster_image" id="poster_image"
                            accept="image/*,.pdf"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#FF9B45]">
                        <p class="text-xs text-gray-500 mt-1">Accepted formats: Images (JPG, PNG, etc.) and PDF.</p>
                    </div>

                    <!-- Multiple Images (new) -->
                    <div>
                        <label for="poster_images" class="block text-sm font-medium text-gray-700 mb-1">Additional Images (you can select multiple)</label>
                        <input type="file" name="poster_images[]" id="poster_images" multiple
                               accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#FF9B45]">
                        <p class="text-xs text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple images. Up to 10 images.</p>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category_id" id="admin_category_id" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#FF9B45]" required onchange="toggleAdminCustomCategory()">
                            <option value="">-- Select Category --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Custom Category (hidden by default) -->
                    <div id="admin_customCategoryDiv" class="hidden">
                        <label for="custom_category" class="block text-sm font-medium text-gray-700 mb-1">Custom Category</label>
                        <input type="text" name="custom_category" id="admin_custom_category" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#FF9B45]" placeholder="Enter custom category name">
                    </div>
                    <!-- Approval -->
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" name="is_approved" id="is_approved" class="rounded border-gray-300 text-[#FF9B45] focus:ring-[#FF9B45]" required>
                        <label for="is_approved" class="text-sm text-gray-700">Mark as Approved</label>
                    </div>
                    <!-- Submit -->
                    <div class="pt-2">
                        <button type="submit" class="w-full sm:w-auto bg-[#F15B24] hover:bg-[#FF9B45] text-white font-semibold px-6 py-2 rounded-md shadow-sm transition">
                            Post Announcement
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <!-- All Posts Panel -->
        <section id="allPosts" class="panel hidden">
            <h1 class="text-2xl font-bold mb-6">All Announcements</h1>
            <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <input type="text" id="allPostsSearch" placeholder="Search moderators..."
                class="w-full sm:w-1/2 p-2 border border-gray-300 rounded" onkeyup="filterTableRows('allPostsSearch', '#allPosts table tbody tr')">
            </div>

            <!-- Announcements Table -->
            <div class="bg-white shadow rounded-lg overflow-y-auto max-h-[700px]">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-100 text-gray-700 font-medium">
                        <tr>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-left">Poster</th>
                            <th class="px-6 py-3 text-left">Title</th>
                            <th class="px-6 py-3 text-left">Date</th>
                            <th class="px-6 py-3 text-left">Posted By</th>
                            <th class="px-6 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-gray-700">
                        @forelse ($announcements as $post)
                            <tr class="hover:bg-gray-50 transition">
                                <!-- Status -->
                                <td class="px-6 py-3">
                                    @if ($post->status === 'approved')
                                        <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded">Approved</span>
                                    @elseif ($post->status === 'rejected')
                                        <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded">Rejected</span>
                                    @else
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded">Pending</span>
                                    @endif
                                </td>

                                <!-- Poster Image -->
                                <td class="px-6 py-3">
                                    @if($post->poster_image)
                                        @php
                                            $postPosterRelative = str_contains($post->poster_image, '/')
                                                ? ltrim($post->poster_image, '/')
                                                : 'posters/' . $post->poster_image;
                                            $ext = strtolower(pathinfo($postPosterRelative, PATHINFO_EXTENSION));
                                        @endphp
                                        @if(in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']))
                                            <img src="{{ asset('storage/' . $postPosterRelative) }}"
                                                alt="Poster Image"
                                                class="w-20 h-20 object-cover rounded shadow">
                                        @elseif($ext === 'pdf')
                                            <a href="{{ asset('storage/' . $postPosterRelative) }}" target="_blank" class="inline-block">
                                                <img src="{{ asset('image/icon/pdf-(1).svg') }}" alt="PDF File" class="w-[90px] h-[90px] mx-auto">
                                                {{-- <span class="block text-xs text-gray-500 mt-1">PDF</span> --}}
                                            </a>
                                        @else
                                            <span class="text-gray-400 italic text-sm">No image</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400 italic text-sm">No image</span>
                                    @endif
                                </td>

                                <td class="px-6 py-3 font-semibold">{{ $post->title }}</td>
                                <td class="px-6 py-3">{{ $post->created_at->format('Y-m-d') }}</td>
                                <td class="px-6 py-3">{{ $post->user->name ?? 'Unknown' }}</td>

                                <!-- Actions -->
                                <td class="px-6 py-3 space-x-2">
                                    @if($post->status === 'rejected')
                                        @php
                                            $approval = $post->approvals()->where('status', 'rejected')->first();
                                        @endphp
                                        <button onclick="showRejectionReasonModal({{ json_encode($approval->rejection_reason ?? 'No reason provided') }})"
                                               class="px-3 py-1 bg-red-100 text-red-700 text-xs rounded hover:bg-red-200 transition">
                                          View Reason
                                        </button>
                                        <a href="{{ route('announcements.edit', $post->id) }}" class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs rounded hover:bg-yellow-200 transition">Edit</a>
                                    @endif
                                    <a href="{{ route('announcements.show', $post->id) }}" class="px-3 py-1 bg-blue-100 text-blue-700 text-xs rounded hover:bg-blue-200 transition">View</a>
                                    <form method="POST" action="{{ route('announcements.destroy', $post->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this announcement?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-100 text-red-700 text-xs rounded hover:bg-red-200 transition">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    No announcements found.
                                </td>
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
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($moderators as $mod)
                            <tr>
                                <td class="px-6 py-4">{{ $mod->name }}</td>
                                <td class="px-6 py-4">{{ $mod->email }}</td>
                                <td class="px-6 py-4 capitalize">{{ $mod->role }}</td>
                                <td class="px-6 py-4 space-x-2">
                                    <a href="{{ route('moderators.show', $mod->id) }}" class="text-blue-600 hover:underline">View</a>
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
                <div class="w-full sm:w-1/2 flex items-center gap-2">
                    <input type="text" id="studentSearch" placeholder="Search students..."
                    class="w-full p-2 border border-gray-300 rounded"
                    onkeyup="applyStudentFilters()">

                    <div class="relative">
                        <button type="button" id="courseFilterBtn" onclick="toggleCourseFilterDropdown()" class="px-3 py-2 border border-gray-300 rounded hover:bg-gray-50">Courses</button>
                        <div id="courseFilterDropdown" class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded shadow-md p-2 z-[90] hidden">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-semibold">Courses</span>
                                <button type="button" class="text-xs text-gray-500 hover:text-gray-700" onclick="clearAllCourseFilters()">Clear</button>
                            </div>
                            <div class="max-h-48 overflow-y-auto space-y-1 text-sm">
                                <label class="flex items-center gap-2"><input type="checkbox" value="BSIT" onchange="onCourseFilterChange(this)"> <span>BSIT</span></label>
                                <label class="flex items-center gap-2"><input type="checkbox" value="BSA" onchange="onCourseFilterChange(this)"> <span>BSA</span></label>
                                <label class="flex items-center gap-2"><input type="checkbox" value="BEED" onchange="onCourseFilterChange(this)"> <span>BEED</span></label>
                                <label class="flex items-center gap-2"><input type="checkbox" value="BSED" onchange="onCourseFilterChange(this)"> <span>BSED</span></label>
                                <label class="flex items-center gap-2"><input type="checkbox" value="BSHM" onchange="onCourseFilterChange(this)"> <span>BSHM</span></label>
                                <label class="flex items-center gap-2"><input type="checkbox" value="BSTM" onchange="onCourseFilterChange(this)"> <span>BSTM</span></label>
                                <label class="flex items-center gap-2"><input type="checkbox" value="BSE" onchange="onCourseFilterChange(this)"> <span>BSE</span></label>
                                <label class="flex items-center gap-2"><input type="checkbox" value="BSBA" onchange="onCourseFilterChange(this)"> <span>BSBA</span></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    {{-- <button class="px-4 py-2 bg-[#E17C5F] text-white rounded">Add Student</button> --}}
                    <button onclick="showImportStudentModal()" class="px-4 py-2 bg-[#E17C5F] text-white rounded">Import Students</button>
                    <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-semibold rounded-full bg-slate-200 text-slate-700">{{ $totalStudents }}</span>
                </div>
            </div>

            <div id="activeCourseChips" class="flex flex-wrap items-center gap-2 mb-3"></div>

            <!-- Students Table -->
            <div class="bg-white rounded shadow overflow-x-auto mb-6">
                <!-- Added scroll behavior -->
                <div class="max-h-[500px] overflow-y-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Name</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Email</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Course</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Role</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Created</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($students as $student)
                            <tr data-course="{{ $student->department->name ?? 'N/A' }}">
                                <td class="px-6 py-4">{{ $student->name }}</td>
                                <td class="px-6 py-4">{{ $student->email }}</td>
                                <td class="px-6 py-4">{{ $student->department->name ?? 'N/A' }}</td>
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
                                    <a href="{{ route('account.edit', $student->id) }}" class="text-yellow-600 hover:underline">Edit</a>

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
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No students found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Import Students Modal -->
            <div id="importStudentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[80] px-4 items-center justify-center">
                <div class="bg-white p-6 rounded shadow max-w-xl w-full relative">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold">Import Students</h2>
                        <button onclick="closeImportStudentModal()" class="text-gray-500 hover:text-gray-700 text-2xl leading-none">&times;</button>
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
            </div>
        </section>

        <!-- Faculty Accounts Panel -->
        <section id="faculty" class="panel hidden">
            <h1 class="text-2xl font-bold mb-6">Faculty Accounts</h1>

            <!-- Search + Action Buttons -->
            <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <input type="text" id="facultySearch" placeholder="Search faculty..."
                class="w-full sm:w-1/2 p-2 border border-gray-300 rounded"
                onkeyup="filterTableRows('facultySearch', '#faculty table tbody tr')">
                <div class="flex items-center gap-2">
                    <button onclick="showImportFacultyModal()" class="px-4 py-2 bg-[#E17C5F] text-white rounded">Import Faculty</button>
                    {{-- <button class="px-4 py-2 bg-[#E17C5F] text-white rounded">Add Faculty</button> --}}
                    <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-semibold rounded-full bg-slate-200 text-slate-700">{{ $totalFaculty }}</span>
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
                <!-- 👇 Added scroll container -->
                <div class="max-h-[500px] overflow-y-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Name</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Email</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Department</th>
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
            </div>

            <!-- Import Faculty Modal -->
            <div id="importFacultyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[80] px-4 items-center justify-center mb-6">
                <div class="bg-white p-6 rounded shadow max-w-xl w-full relative">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold">Import Faculty</h2>
                        <button onclick="closeImportFacultyModal()" class="text-gray-500 hover:text-gray-700 text-2xl leading-none">&times;</button>
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
            </div>
        </section>

        {{-- Password Reset Requests Panel --}}
        <section id="password-requests" class="panel hidden">
            <h1 class="text-2xl font-bold mb-6">Password Reset Requests</h1>
            <p class="text-gray-600 mb-6">Review and handle password reset requests from users.</p>

            <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <input type="text" id="passwordRequestSearch" placeholder="Search requests..."
                    class="w-full sm:w-1/2 p-2 border border-gray-300 rounded" onkeyup="filterTableRows('passwordRequestSearch', '#password-requests table tbody tr')">
            </div>

            <div class="bg-white shadow rounded-lg overflow-y-auto max-h-[600px]">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-100 text-gray-700 font-medium">
                        <tr>
                            <th class="px-6 py-3 text-left">User</th>
                            <th class="px-6 py-3 text-left">Email</th>
                            <th class="px-6 py-3 text-left">Role</th>
                            <th class="px-6 py-3 text-left">Requested At</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-gray-700">
                        @forelse (($passwordRequests ?? []) as $reset)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-3 font-medium">{{ $reset->user->name ?? 'Unknown User' }}</td>
                                <td class="px-6 py-3">{{ $reset->email }}</td>
                                <td class="px-6 py-3">
                                    <span class="px-2 py-1 text-xs rounded
                                        {{ optional($reset->user)->role === 'admin' ? 'bg-purple-100 text-purple-700' :
                                           (optional($reset->user)->role === 'student' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700') }}">
                                        {{ ucfirst(optional($reset->user)->role ?? 'N/A') }}
                                    </span>
                                </td>
                                <td class="px-6 py-3">{{ optional($reset->requested_at)->format('Y-m-d H:i') }}</td>
                                <td class="px-6 py-3">
                                    @php
                                        $statusStyles = [
                                            'pending' => 'bg-yellow-100 text-yellow-700',
                                            'approved' => 'bg-green-100 text-green-700',
                                            'declined' => 'bg-red-100 text-red-700',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 text-xs rounded {{ $statusStyles[$reset->status] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ ucfirst($reset->status) }}
                                    </span>
                                    @if($reset->handled_by)
                                        <div class="text-xs text-gray-500 mt-1">
                                            Handled by: {{ optional($reset->handler)->name ?? 'N/A' }}
                                        </div>
                                    @endif
                                    @if($reset->decline_reason)
                                        <div class="text-xs text-red-500 mt-1">
                                            Reason: {{ $reset->decline_reason }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-3 space-x-2">
                                    @if($reset->status === 'pending')
                                        <form method="POST" action="{{ route('admin.password-resets.approve', $reset) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-green-100 text-green-700 text-xs rounded hover:bg-green-200 transition">
                                                Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.password-resets.decline', $reset) }}" class="inline decline-request-form">
                                            @csrf
                                            <input type="hidden" name="reason" value="">
                                            <button type="button" onclick="promptDeclineReason(this)" class="px-3 py-1 bg-red-100 text-red-700 text-xs rounded hover:bg-red-200 transition">
                                                Decline
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-500">Handled</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    No password reset requests found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
                    @php
                        $adminProfile = Auth::user()->profile_picture ?? null;
                        $adminProfilePath = $adminProfile ? 'profile_pictures/' . basename($adminProfile) : null;
                        $adminProfileUrl = $adminProfilePath
                            ? asset('storage/' . $adminProfilePath)
                            : 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTezfOC3OmGvUnyrEpDPez1zyz-ibH_Hn7ZEctdJs3-IohUKEpT3mA5XuVfBpjhuRw16ws';
                    @endphp
                    <div class="flex items-center gap-6">
                        <img id="profile-preview"
                            src="{{ $adminProfileUrl }}"
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

    {{-- Activity Logs Panel --}}
        <section id="activityLogs" class="h-screen panel hidden">
            <h1 class="text-2xl font-bold mb-2">Activity Logs</h1>
            <p class="text-gray-600 mb-6 text-sm">
                Track recent activities and system changes performed by users and moderators.
            </p>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row justify-end gap-2 mb-4 px-1 sm:px-4">
                <a href="{{ route('logs.index') }}" target="_blank"
                class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
                    Full Logs
                </a>
                <a href="{{ route('activityLogs.export', ['format' => 'pdf']) }}" target="_blank"
                class="px-4 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition">
                    Download PDF
                </a>
                {{-- <a href="{{ route('activityLogs.export', ['format' => 'txt']) }}" target="_blank"
                class="px-4 py-2 bg-gray-600 text-white text-sm rounded hover:bg-gray-700 transition">
                    Download TXT
                </a> --}}
            </div>

            {{-- Table --}}
            <div class="bg-white shadow rounded-lg overflow-y-auto max-h-[500px]">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-100 text-gray-700 font-medium">
                        <tr>
                            <th class="px-6 py-3 text-left">Timestamp</th>
                            <th class="px-6 py-3 text-left">User</th>
                            <th class="px-6 py-3 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-gray-700">
                        @forelse ($logs as $log)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-3 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($log->timestamp)->timezone('Asia/Manila')->format('Y-m-d H:i') }}
                                </td>
                                <td class="px-6 py-3 font-medium">
                                    {{ $log->user->name ?? 'Guest' }}
                                    <div class="text-xs text-gray-500">{{ ucfirst($log->user->role ?? 'guest') }}</div>
                                </td>
                                <td class="px-6 py-3">
                                @php
                                    $actionIcons = [
                                        'approved' => '✅',
                                        'rejected' => '❌',
                                        'edited'   => '✏️',
                                        'viewed'   => '👁️',
                                        'created'  => '📝',
                                    ];
                                    $icon = $actionIcons[$log->action] ?? '🔍';
                                @endphp

                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-semibold">
                                    {{ $icon }} {{ ucfirst($log->action) }}
                                </span>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    No activity logs found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <!-- Rejection Reason Modal -->
    <div id="rejectionReasonModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[80] px-4">
        <div class="bg-white p-8 rounded-2xl w-full max-w-2xl shadow-2xl relative">
            {{-- <button onclick="closeRejectionReasonModal()"
                    class="absolute top-4 right-6 text-gray-400 hover:text-black text-3xl font-bold transition z-[90]">
                &times;
            </button> --}}

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

    <!-- Rejection Reason Modal -->
    <div id="rejectionReasonModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[80] px-4">
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

    <script src="{{ asset('js/admin.js') }}"></script>
</body>
</html>
