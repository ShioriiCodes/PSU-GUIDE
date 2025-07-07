<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'PSU-GUIDE')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css') {{-- or your CSS setup --}}
    <script src="https://unpkg.com/alpinejs" defer></script>
</head>
<body class="min-h-screen flex flex-col bg-gray-50 bg-[#F4E7E1]">
<!-- Optional Header -->
<header class="bg-white shadow-md py-6 px-6 flex justify-center items-center">
    <div class="flex items-center w-full max-w-3xl mx-auto">
        <img src="{{ asset('logo/iconb.png') }}" alt="PSU Logo" class="h-12 w-12 mr-4">
        <h1 class="text-3xl font-extrabold text-gray-800 text-center flex-1">
            @yield('header', 'PSU-GUIDE')
        </h1>
        <img src="{{ asset('logo/iconb.png') }}" alt="PSU Logo" class="h-12 w-12 ml-4">
    </div>
</header>

    <!-- Main Content -->
    <main class="flex-1 px-6 py-8 bg-[#F4E7E1]">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#D9481F] text-white text-center py-4">
        © 2025 PSU-GUIDE | Palawan State University Quezon Campus<br>
        Contact: <a href="mailto:psuguide@gmail.com" class="underline">psuguide@gmail.com</a>
    </footer>

</body>
</html>
