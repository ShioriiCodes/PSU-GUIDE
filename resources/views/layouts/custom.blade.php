<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'PSU-GUIDE')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="icon" href="{{ asset('logo/logo.ico') }}" type="image/png">
</head>
<body class="bg-[#dddddd] text-[#521C0D]">
    <!-- Navbar -->
    <nav class="bg-[#ffffff] text-black p-4 shadow-md sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center w-full">
            <!-- Logo -->
            <div class="flex items-center space-x-2">
                <img src="{{asset('logo/logo1.png')}}" alt="PSU-GUIDE LOGO" class="h-14 w-auto object-contain" />
                <div class="text-2xl font-bold flex items-center h-14">PSU-GUIDE</div>
            </div>

            <!-- Hamburger Button (mobile) -->
            <button id="menu-toggle" class="lg:hidden text-[#521C0D] focus:outline-none">
                <svg class="w-6 h-6 fill-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <ul id="nav-links"
                class="hidden font-bold lg:flex flex-col lg:flex-row items-center absolute lg:static top-full left-0 w-full lg:w-auto bg-[#F4E7E1] lg:bg-transparent space-y-4 lg:space-y-0 lg:space-x-6 font-poppins p-4 lg:p-0">
                <!-- Static Links -->
                <li><a href="{{ route('home') }}" class="nav-link pb-1 border-b-4 border-transparent hover:border-[#FF9B45] transition">Home</a></li>
                <li><a href="{{ route('about') }}" class="nav-link pb-1 border-b-4 border-transparent hover:border-[#FF9B45] transition">About</a></li>
                <li><a href="{{ route('announcement') }}" class="nav-link pb-1 border-b-4 border-transparent hover:border-[#FF9B45] transition">Announcements</a></li>
                <li><a href="{{ route('contact') }}" class="nav-link pb-1 border-b-4 border-transparent hover:border-[#FF9B45] transition">Contact</a></li>

                <!-- If NOT Logged In -->
                @guest
                <li>
                    <a href="{{ route('login') }}" class="bg-[#F15B24] text-white px-6 py-2 rounded-md font-[inter] hover:bg-[#FF9B45] transition">
                        Log In
                    </a>
                </li>
                @endguest
                @auth
                <li class="relative" x-data="{ open: false }">
                    <!-- Profile Button -->
                    <button @click="open = !open" class="flex items-center focus:outline-none">
                        <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('image/icon/student.png') }}"
                            class="w-14 h-14 rounded-full border-2 border-white hover:border-[#FF9B45] transition"
                            alt="Profile">
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false"
                        x-transition
                        class="absolute right-0 mt-3 w-64 bg-white text-gray-700 rounded-md shadow-lg p-4 z-50"
                        style="display: none;">
                        <div class="text-sm font-semibold">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-gray-500 mb-2">{{ Auth::user()->email }}</div>

                        <hr class="my-2">

                        <ul class="space-y-2 text-sm">
                            <li><a href="{{ route('profile.edit') }}" class="block hover:text-[#F15B24]">Profile</a></li>
                            <li><a href="#" class="block hover:text-[#F15B24]">Notifications</a></li>
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

    @yield('content')

    <!-- Footer -->
    <footer class="bg-[#F15B24] text-white text-center px-4 py-6 text-sm sm:text-base">
        <p>&copy; 2025 PSU-GUIDE | Palawan State University Quezon Campus</p>
        <p>Contact: <a href="mailto:psuguide@gmail.com" class="underline hover:text-[#FF9B45]">psuguide@gmail.com</a></p>
    </footer>

    <script src="{{ asset('js/script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
