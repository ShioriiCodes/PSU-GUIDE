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
            <ul id="nav-links" class="hidden lg:flex flex-col lg:flex-row items-center absolute lg:static top-full left-0 w-full lg:w-auto bg-white/90 backdrop-blur-lg lg:bg-transparent space-y-4 lg:space-y-0 lg:space-x-8 p-6 lg:p-0 shadow-2xl lg:shadow-none rounded-b-2xl lg:rounded-none animate-slide-down">
                <li><a href="{{ route('home') }}" class="nav-link pb-2 border-b-4 border-transparent hover:border-[#FF9B45] transition-all duration-300 text-[#521C0D] hover:text-[#D5451B] font-medium px-2 py-1 rounded hover:bg-[#F4E7E1]">Home</a></li>
                <li><a href="{{ route('about') }}" class="nav-link pb-2 border-b-4 border-transparent hover:border-[#FF9B45] transition-all duration-300 text-[#521C0D] hover:text-[#D5451B] font-medium px-2 py-1 rounded hover:bg-[#F4E7E1]">About</a></li>
                <li>
                    <a href="{{ route('announcement') }}" class="nav-link pb-2 border-b-4 border-transparent hover:border-[#FF9B45] transition-all duration-300 text-[#521C0D] hover:text-[#D5451B] font-medium px-2 py-1 rounded hover:bg-[#F4E7E1] relative">
                        Announcements
                        @auth
                            @php
                                $lastVisited = Auth::user()->last_visited_announcements;
                                $newCount = $lastVisited ? \App\Models\Announcement::where('created_at', '>', $lastVisited)->count() : 0;
                                $badgeText = $newCount > 9 ? '9+' : $newCount;
                            @endphp
                            @if($newCount > 0)
                                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center animate-bounce">{{ $badgeText }}</span>
                            @endif
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
                        <button @click="open = !open" class="relative focus:outline-none transition-all duration-200 hover:scale-105 p-1 rounded-full hover:bg-[#F4E7E1] active:scale-95" aria-label="User menu">
                            <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('image/icon/student.png') }}"
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
                                <li><a href="{{ $dashboardRoute ?? '#' }}" class="block text-[#521C0D] hover:text-[#D5451B] transition-colors py-2 px-3 rounded hover:bg-[#F4E7E1]">{{ $label ?? 'Profile' }}</a></li>
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
    <footer class="bg-gradient-to-br from-[#D5451B] via-[#FF9B45] to-[#D5451B] text-white px-6 py-12 mt-16 relative overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="container mx-auto max-w-[1200px] text-center relative z-10">
            <div class="mb-6">
                <img src="{{ asset('logo/logo2-1.png') }}" alt="PSU-GUIDE LOGO" class="h-16 w-auto mx-auto mb-3 opacity-95 hover:opacity-100 transition-opacity" />
                <p class="text-xl font-bold">&copy; 2025 PSU-GUIDE | Palawan State University Quezon Campus</p>
                <p class="text-sm mt-2 opacity-90">Empowering education through innovation.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <div>
                    <h3 class="font-semibold mb-2">Quick Links</h3>
                    <ul class="space-y-1 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-gray-200 transition-colors">Home</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-gray-200 transition-colors">About</a></li>
                        <li><a href="{{ route('announcement') }}" class="hover:text-gray-200 transition-colors">Announcements</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-gray-200 transition-colors">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold mb-2">Contact Info</h3>
                    <p class="text-sm">Email: <a href="mailto:psuguide.info@gmail.com" class="underline hover:text-gray-200 transition-colors">psuguide.info@gmail.com</a></p>
                    <p class="text-sm mt-1">Phone: +63 912 345 6789</p>
                </div>
                <div>
                    <h3 class="font-semibold mb-2">Follow Us</h3>
                    <div class="flex justify-center space-x-4">
                        <a href="https://facebook.com/psuquezon" class="hover:text-gray-200 transition-colors" aria-label="Facebook"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
                        <a href="#" class="hover:text-gray-200 transition-colors" aria-label="Twitter"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg></a>
                    </div>
                </div>
            </div>
            <p class="text-xs opacity-75">&copy; 2025 PSU-GUIDE. All rights reserved.</p>
        </div>
    </footer>

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
    </script>

</body>
</html>
