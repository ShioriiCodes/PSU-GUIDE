<!DOCTYPE html>
<html lang="en">
    <meta charset="UTF-8">
    <title>@yield('title', 'PSU-GUIDE')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Inter font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Poppins font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css') {{-- or your CSS setup --}}
    <script src="https://unpkg.com/alpinejs" defer></script>
    <link rel="icon" href="{{ asset('logo/logo.ico') }}" type="image/x-icon">
</head>
<body class=" text-white  bg-white">
<!-- Optional Header -->
<header class="bg-white shadow-md py-6 px-6 flex justify-center items-center">
    <div class="flex items-center w-full max-w-3xl mx-auto">
        <img src="{{asset('logo/logo2-1.png')}}" alt="PSU Logo" class="h-12 w-12 mr-4">
        <h1 class="text-3xl font-extrabold text-gray-800 text-center flex-1">
            @yield('header', 'PSU-GUIDE')
        </h1>
        <img src="{{asset('logo/logo2-1.png')}}" alt="PSU Logo" class="h-12 w-12 ml-4">
    </div>
</header>

    <!-- Main Content -->
    <main class="flex-1 px-6 py-8 bg-[#F4E7E1] h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#D9481F] text-white text-center py-4">
        © 2025 PSU-GUIDE | Palawan State University Quezon Campus<br>
        Contact: <a href="https://mail.google.com/mail/?view=cm&fs=1&to=psuguide.info@gmail.com" target="_blank" rel="noopener" class="underline     hover:text-[#FF9B45] transition">
            psuguide.info@gmail.com</a>
        
    </footer>

</body>
</html>
