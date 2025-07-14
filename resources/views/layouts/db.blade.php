<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Login')</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Inter font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <!-- Poppins font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bg-[#F4E7E1] text-[#521C0D]">
    
  
  @yield('content')

  <footer class="bg-[#D5451B] text-white text-center px-4 py-6 text-sm sm:text-base">
    <p>&copy; 2025 PSU-GUIDE | Palawan State University Quezon Campus</p>
    <p>Contact: <a href="mailto:psuguide@gmail.com" class="underline hover:text-gray-200">psuguide@gmail.com</a></p>
  </footer>

  <script src="{{ asset('js/profile.js') }}"></script>
</body>
</html>
