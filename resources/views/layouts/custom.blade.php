<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'PSU-GUIDE')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="icon" href="{{ asset('logo/logo.ico') }}" type="image/x-icon">
</head>
<body class="bg-[#e6e6e6] text-[#521C0D]">
    <!-- Navbar -->
    <nav class="bg-[#ffffff] text-black p-4 shadow-md sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center w-full">
            <!-- Logo -->
            <div class="flex items-center space-x-2">
                <img src="{{ asset('logo/logo2-1.png') }}" alt="PSU-GUIDE LOGO" class="h-14 w-auto object-contain" />
                <div class="text-2xl font-bold flex items-center h-14">PSU-GUIDE</div>
            </div>

            <!-- Hamburger -->
            <button id="menu-toggle" class="lg:hidden text-[#521C0D] focus:outline-none">
                <svg class="w-6 h-6 fill-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Nav links -->
            <ul id="nav-links" class="hidden font-bold lg:flex flex-col lg:flex-row items-center absolute lg:static top-full left-0 w-full lg:w-auto bg-[#F4E7E1] lg:bg-transparent space-y-4 lg:space-y-0 lg:space-x-6 font-poppins p-4 lg:p-0">
                <li><a href="{{ route('home') }}" class="nav-link pb-1 border-b-4 border-transparent hover:border-[#FF9B45]">Home</a></li>
                <li><a href="{{ route('about') }}" class="nav-link pb-1 border-b-4 border-transparent hover:border-[#FF9B45]">About</a></li>
                <li><a href="{{ route('announcement') }}" class="nav-link pb-1 border-b-4 border-transparent hover:border-[#FF9B45]">Announcements</a></li>
                <li><a href="{{ route('contact') }}" class="nav-link pb-1 border-b-4 border-transparent hover:border-[#FF9B45]">Contact</a></li>

                @guest
                    <li>
                        <a href="{{ route('login') }}" class="bg-[#F15B24] text-white px-6 py-2 rounded-md hover:bg-[#FF9B45] transition">
                            Log In
                        </a>
                    </li>
                @endguest

                @auth
                    @php
                        $notifications = Auth::user()->notifications()->latest()->take(20)->get();
                        $unreadCount = Auth::user()->unreadNotifications->count();
                        $role = Auth::user()->role;
                        $dashboardRoute = match ($role) {
                            'admin' => route('dashboard.admin'),
                            'usg' => route('dashboard.usg'),
                            'registrar' => route('dashboard.registrar'),
                            default => route('user.profile'),
                        };
                        $label = match ($role) {
                            'admin' => 'Admin Panel',
                            'usg' => 'USG Panel',
                            'registrar' => 'Registrar Panel',
                            default => 'Profile',
                        };
                    @endphp
                    <li class="relative" x-data="{ open: false, showNotifs: false }">
                        <!-- Profile Button -->
                        <button @click="open = !open" class="relative focus:outline-none">
                            <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('image/icon/student.png') }}"
                                class="w-14 h-14 rounded-full border-2 border-white hover:border-[#FF9B45]" alt="Profile">
                            @if ($unreadCount > 0)
                                <span class="absolute top-0 right-0 block h-3 w-3 rounded-full bg-red-500 ring-2 ring-white"></span>
                            @endif
                        </button>

                        <!-- Dropdown -->
                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute right-0 mt-3 w-64 bg-white text-gray-700 rounded-md shadow-lg p-4 z-50">
                            <div class="text-sm font-semibold">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-gray-500 mb-2">{{ Auth::user()->email }}</div>

                            <hr class="my-2" />

                            <ul class="space-y-2 text-sm">
                                <li><a href="{{ $dashboardRoute }}" class="block hover:text-[#F15B24]">{{ $label }}</a></li>

                                <!-- Notifications -->
                                <li x-data="{ showNotifs: false }">
                                    <button @click="showNotifs = !showNotifs" class="w-full text-left hover:text-[#F15B24]">
                                        Notifications
                                    </button>

                                    <div x-show="showNotifs"
                                        @click.away="showNotifs = false"
                                        x-transition
                                        x-init="$watch('showNotifs', value => { if (value) fetch('{{ route('notifications.markAllRead') }}') })"
                                        class="mt-2 max-h-96 overflow-y-auto bg-white border border-gray-200 rounded-md p-4 shadow-lg z-50">

                                        <h3 class="text-sm font-semibold mb-2">Your Notifications</h3>

                                        @forelse ($notifications as $notification)
                                            @php
                                                $announcementId = $notification->data['announcement_id'] ?? null;
                                                $commentId = $notification->data['comment_id'] ?? null;
                                                $announcement = $announcementId ? \App\Models\Announcement::find($announcementId) : null;
                                            @endphp

                                            @if ($announcement)
                                                <div class="mb-3 p-2 bg-gray-100 rounded text-sm text-gray-800">
                                                    <a href="{{ route('announcement') }}?modal=comments&announcement_id={{ $announcementId }}&comment_id={{ $commentId }}">
                                                        {{ $notification->data['message'] }}
                                                    </a>
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </div>
                                                </div>
                                            @endif
                                        @empty
                                            <p class="text-sm text-gray-500">No notifications</p>
                                        @endforelse
                                    </div>
                                </li>

                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left hover:text-[#F15B24]">Log Out</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endauth
            </ul>
        </div>
    </nav>

    <!-- Page Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="bg-[#F15B24] text-white text-center px-4 py-6 text-sm sm:text-base">
        <p>&copy; 2025 PSU-GUIDE | Palawan State University Quezon Campus</p>
        <p>
            Contact: <a href="mailto:psuguide.info@gmail.com"
                        class="underline hover:text-[#FF9B45]">psuguide.info@gmail.com</a>
        </p>
    </footer>

    <script src="{{ asset('js/script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

</body>
</html>
