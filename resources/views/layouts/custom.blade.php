<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'PSU-GUIDE')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="icon" href="{{ asset('logo/logo.ico') }}" type="image/x-icon">
</head>
<body class="bg-[#e6e6e6] text-[#521C0D] font-[Poppins] transition-all duration-300">
    <!-- Navbar -->
    <nav class="bg-white/95 backdrop-blur-md text-black p-4 shadow-xl sticky top-0 z-50 border-b border-gray-200">
        <div class="container mx-auto flex justify-between items-center w-full max-w-[1200px]">
            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <img src="{{ asset('logo/logo2-1.png') }}" alt="PSU-GUIDE LOGO" class="h-12 sm:h-14 w-auto object-contain transition-transform hover:scale-105" />
                <div class="text-xl sm:text-2xl font-bold flex items-center h-12 sm:h-14 text-[#521C0D] hover:text-[#D5451B] transition-colors">PSU-GUIDE</div>
            </div>

            <!-- Hamburger -->
            <button id="menu-toggle" class="lg:hidden text-[#521C0D] focus:outline-none hover:text-[#D5451B] transition-all duration-200 p-2 rounded-lg hover:bg-[#F4E7E1] active:scale-95" aria-label="Toggle menu" aria-expanded="false">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Nav links -->
            <ul id="nav-links" class="hidden lg:flex flex-col lg:flex-row items-center absolute lg:static top-full left-0 w-full lg:w-auto bg-white/90 lg:bg-transparent space-y-4 lg:space-y-0 lg:space-x-8 p-6 lg:p-0 shadow-2xl lg:shadow-none rounded-b-2xl lg:rounded-none animate-slide-down">
                <li><a href="{{ route('home') }}" class="nav-link pb-2 border-b-4 border-transparent hover:border-[#FF9B45] transition-all duration-300 text-[#521C0D] hover:text-[#D5451B] font-medium px-2 py-1 rounded hover:bg-[#F4E7E1]">Home</a></li>
                <li><a href="{{ route('about') }}" class="nav-link pb-2 border-b-4 border-transparent hover:border-[#FF9B45] transition-all duration-300 text-[#521C0D] hover:text-[#D5451B] font-medium px-2 py-1 rounded hover:bg-[#F4E7E1]">About</a></li>
                <li>
                    <a id="annNavLink" href="{{ route('announcement') }}" class="nav-link pb-2 border-b-4 border-transparent hover:border-[#FF9B45] transition-all duration-300 text-[#521C0D] hover:text-[#D5451B] font-medium px-2 py-1 rounded hover:bg-[#F4E7E1] relative">
                        Announcements
                        @auth
                            @php
                                $user = Auth::user();
                                $lastVisited = $user->last_visited_announcements ?: $user->created_at;
                                $newQuery = \App\Models\Announcement::query()
                                    ->where('status', 'approved')
                                    ->where('created_at', '>', $lastVisited)
                                    ->where('posted_by', '!=', $user->id); // exclude own posts

                                // Mirror visibility rules of Announcements page for accuracy
                                if ($user->role === 'student') {
                                    $newQuery->whereHas('category', function ($q) {
                                        $q->whereNotIn('name', ['Memorandum']);
                                    });
                                }
                                // Other roles (admin/registrar/usg/faculty) see all approved

                                $newCount = $newQuery->count();
                                $badgeText = $newCount > 9 ? '9+' : $newCount;
                            @endphp
                            <span id="annNavDot"
                                  class="absolute -top-1 -right-2 w-2.5 h-2.5 bg-red-600 rounded-full {{ $newCount > 0 ? '' : 'hidden' }}"
                                  title="New activity"></span>
                            <span id="annNavCount"
                                  class="absolute -top-2 -right-2 bg-red-500 text-white text-[11px] rounded-full h-5 min-w-[20px] px-1 flex items-center justify-center font-semibold {{ $newCount > 0 ? '' : 'hidden' }}">
                                {{ $badgeText }}
                            </span>
                        @endauth
                    </a>
                </li>
                <li><a href="{{ route('contact') }}" class="nav-link pb-2 border-b-4 border-transparent hover:border-[#FF9B45] transition-all duration-300 text-[#521C0D] hover:text-[#D5451B] font-medium px-2 py-1 rounded hover:bg-[#F4E7E1]">Contact</a></li>

                @guest
                    {{-- Optional: Add login button if needed in future --}}
                @endguest

                @auth
                    <li class="relative ml-4" x-data="{ open: false, showNotifs: false }">
                        <!-- Profile Button -->
                        @php
                            $rawProfile = Auth::user()->profile_picture ?? null;
                            $profilePath = $rawProfile ? 'profile_pictures/' . basename($rawProfile) : null;
                            $profileUrl = $profilePath ? asset('storage/' . $profilePath) : asset('image/icon/student.png');
                        @endphp
                        <button @click="open = !open" class="relative focus:outline-none transition-all duration-200 hover:scale-105 p-1 rounded-full hover:bg-[#F4E7E1] active:scale-95" aria-label="User menu">
                            <img src="{{ $profileUrl }}"
                                class="w-12 h-12 sm:w-14 sm:h-14 rounded-full border-3 border-[#FF9B45] hover:border-[#D5451B] transition-all duration-200 shadow-md hover:shadow-lg" alt="Profile">
                            @if (isset($unreadCount) && $unreadCount > 0)
                                <span class="absolute top-0 right-0 block h-3 w-3 rounded-full bg-red-500 ring-2 ring-white animate-pulse"></span>
                            @endif
                        </button>

                        <!-- Dropdown -->
                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="transform opacity-0 scale-90" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-90"
                            class="absolute right-0 mt-3 w-72 bg-white/95 backdrop-blur-lg text-gray-800 rounded-xl shadow-2xl p-5 z-50 border border-gray-200">
                            <div class="text-base font-semibold text-[#521C0D] mb-1">{{ Auth::user()->name }}</div>
                            <div class="text-sm text-gray-600 mb-4">{{ Auth::user()->email }}</div>

                            <hr class="my-4 border-gray-300" />

                            <ul class="space-y-3 text-sm">
                                @php
                                    $userRole = Auth::user()->role;
                                    $dashboardInfo = match($userRole) {
                                        'admin' => ['route' => 'dashboard.admin', 'label' => 'Admin Dashboard'],
                                        'registrar' => ['route' => 'dashboard.registrar', 'label' => 'Registrar Dashboard'],
                                        'usg' => ['route' => 'dashboard.usg', 'label' => 'USG Dashboard'],
                                        'faculty' => ['route' => 'user.profile', 'label' => 'Faculty Dashboard'],
                                        'student' => ['route' => 'user.profile', 'label' => 'Student Dashboard'],
                                        default => ['route' => 'dashboard', 'label' => 'Dashboard']
                                    };
                                @endphp
                                <li><a href="{{ route($dashboardInfo['route']) }}" class="block text-[#521C0D] hover:text-[#D5451B] transition-colors py-2 px-3 rounded hover:bg-[#F4E7E1]">{{ $dashboardInfo['label'] }}</a></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left text-[#521C0D] hover:text-[#D5451B] transition-colors py-2 px-3 rounded hover:bg-[#F4E7E1]">Log Out</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endauth
            </ul>
        </div>
    </nav>

    {{-- Floating Scroll to Top Button --}}
    <button onclick="scrollToTop()"
            id="scrollTopBtn"
            class="fixed bottom-6 right-6 z-50 bg-gradient-to-br from-[#D5451B] via-[#FF9B45] to-[#D5451B] hover:from-[#FF9B45] hover:to-[#D5451B] text-white p-4 rounded-full shadow-2xl hidden transition-all duration-300 ease-in-out transform hover:scale-125 hover:rotate-12" aria-label="Scroll to top" title="Back to top">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
        </svg>
    </button>

    <!-- Page Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#521C0D] text-white px-6 py-8">
        <div class="container mx-auto max-w-[1200px]">
            <div class="text-center">
                <div class="mb-4">
                    <img src="{{ asset('logo/logo2-1.png') }}" alt="PSU-GUIDE LOGO" class="h-12 w-auto mx-auto mb-2 opacity-90" />
                    <p class="text-lg font-semibold">&copy; 2025 PSU-GUIDE</p>
                    <p class="text-sm text-gray-300">Palawan State University Quezon Campus</p>
                </div>
                <div class="flex flex-col sm:flex-row justify-center items-center space-y-2 sm:space-y-0 sm:space-x-6 mb-4">
                    <a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors">Home</a>
                    <a href="{{ route('about') }}" class="text-gray-300 hover:text-white transition-colors">About</a>
                    <a href="{{ route('announcement') }}" class="text-gray-300 hover:text-white transition-colors">Announcements</a>
                    <a href="{{ route('contact') }}" class="text-gray-300 hover:text-white transition-colors">Contact</a>
                </div>
                <p class="text-xs text-gray-400">&copy; 2025 PSU-GUIDE. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Global Floating Notification Popup -->
    @auth
    <div id="globalNotification" class="fixed bottom-6 right-6 bg-white border border-gray-200 rounded-lg shadow-xl p-4 max-w-sm hidden z-50 cursor-pointer" onclick="openGlobalNotifLink()">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">i</div>
            <div class="flex-1">
                <p class="text-sm font-semibold text-gray-800" id="globalNotifTitle">Notification</p>
                <p class="text-sm text-gray-600" id="globalNotifMessage">You have a new notification.</p>
            </div>
            <span id="globalNotifCount" class="ml-2 inline-flex items-center justify-center min-w-[24px] h-6 px-2 rounded-full bg-red-600 text-white text-xs font-bold hidden"></span>
            <button type="button" onclick="hideGlobalNotif()" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>
    </div>
    @endauth



    <style>
        .animate-slide-down { animation: slideDown 0.3s ease-out; }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeIn 0.3s ease-in; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .border-3 { border-width: 3px; }
    </style>
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        // Enhanced mobile menu toggle with animation
        document.getElementById('menu-toggle').addEventListener('click', function() {
            const navLinks = document.getElementById('nav-links');
                        const isHidden = navLinks.classList.contains('hidden');
                        if (isHidden) {
                            navLinks.classList.remove('hidden');
                            navLinks.classList.add('animate-slide-down');
                            this.setAttribute('aria-expanded', 'true');
                        } else {
                            navLinks.classList.add('hidden');
                            navLinks.classList.remove('animate-slide-down');
                            this.setAttribute('aria-expanded', 'false');
                        }
        });

        // Show button when scrolling down with smoother animation
        window.addEventListener('scroll', () => {
            const scrollBtn = document.getElementById('scrollTopBtn');
            if (window.scrollY > 300) {
                scrollBtn.classList.remove('hidden');
                scrollBtn.classList.add('animate-fade-in');
            } else {
                scrollBtn.classList.add('hidden');
                scrollBtn.classList.remove('animate-fade-in');
            }
        });

        // Scroll to top smoothly
        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Floating notifications: poll for latest unread reply/like and show
        let __lastNotifUrl = null;
        async function pollLatestNotification(){
            try {
                const res = await fetch('/notifications/latest', { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
                if (!res.ok) return;
                const data = await res.json();
                if (!data) return;

                    __lastNotifUrl = data.url || null;
                    const seen = Number(localStorage.getItem('notif_seen_count') || '0');
                    const current = Number(data.unread_count || 0);

                    // Only show if there are more unread than last time we showed
                    if (current > seen) {
                        showGlobalNotif(data.title || 'Notification', data.message || 'You have a new notification', current);
                        // Persist the currently seen count so we don't re-show until it increases
                        localStorage.setItem('notif_seen_count', String(current));
                    }

                    if (current === 0) {
                        // Ensure the popup is hidden everywhere when read
                        hideGlobalNotif();
                        localStorage.setItem('notif_seen_count', '0');
                }
            } catch (e) { /* ignore */ }
        }

        function showGlobalNotif(title, message, count){
            const box = document.getElementById('globalNotification');
            if (!box) return;
            document.getElementById('globalNotifTitle').textContent = title;
            document.getElementById('globalNotifMessage').textContent = message;
            const badge = document.getElementById('globalNotifCount');
            if (typeof count === 'number' && count > 1) {
                badge.textContent = count > 99 ? '99+' : String(count);
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
            box.classList.remove('hidden');
            // auto hide after 6s
            clearTimeout(window.__notifTimer);
            window.__notifTimer = setTimeout(hideGlobalNotif, 6000);
        }

        function hideGlobalNotif(){
            const box = document.getElementById('globalNotification');
            if (box) box.classList.add('hidden');
        }

        function openGlobalNotifLink(){
            if (__lastNotifUrl) {
                fetch('/notifications/read-all', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }})
                  .finally(() => {
                      // Reset seen counter so new notifications will appear
                      localStorage.setItem('notif_seen_count', '0');
                      window.location.href = __lastNotifUrl;
                  });
            }
        }

        @auth
        // Start polling every 20 seconds when authenticated
        setInterval(pollLatestNotification, 20000);
        // Also check once shortly after load
        setTimeout(pollLatestNotification, 2000);

        // Mark announcements as read when clicking the Announcements nav
        const annLink = document.getElementById('annNavLink');
        if (annLink) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
            const markAnnouncementsReadUrl = @json(route('announcements.markRead'));

            const hideAnnouncementBadge = () => {
                const badge = document.getElementById('annNavCount');
                const dot = document.getElementById('annNavDot');
                if (badge) {
                    badge.classList.add('hidden');
                    badge.textContent = '';
                }
                if (dot) {
                    dot.classList.add('hidden');
                }
            };

            const postJson = (url) => fetch(url, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: '{}',
                keepalive: true,
            }).catch(() => {});

            annLink.addEventListener('click', function(ev){
                const isModifiedClick = ev.metaKey || ev.ctrlKey || ev.shiftKey || ev.altKey || ev.button !== 0;

                hideAnnouncementBadge();
                postJson(markAnnouncementsReadUrl);

                if (isModifiedClick) {
                    return;
                }

                ev.preventDefault();

                Promise.allSettled([
                    postJson('/notifications/read-all'),
                ]).finally(() => {
                      localStorage.setItem('notif_seen_count', '0');
                      window.location.href = annLink.href;
                  });
            });
        }
        @endauth
    </script>

</body>
</html>
