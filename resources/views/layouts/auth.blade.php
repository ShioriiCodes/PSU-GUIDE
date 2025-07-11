<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Login')</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&family=Roboto&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="icon" href="{{ asset('logo/logo.ico') }}" type="image/x-icon">
</head>
<body class="bg-[#eaeaea] h-screen">

  @yield('content')

  <!-- Footer -->
  <footer class="bg-[#D5451B] text-white text-center px-4 py-6 text-sm sm:text-base">
    <p>&copy; 2025 PSU-GUIDE | Palawan State University Quezon Campus</p>
    <p>Contact: <a href="https://mail.google.com/mail/?view=cm&fs=1&to=psuguide.info@gmail.com" target="_blank" rel="noopener" class="underline hover:text-[#FF9B45] transition">
              psuguide.info@gmail.com</a></p>
  </footer>

  <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
