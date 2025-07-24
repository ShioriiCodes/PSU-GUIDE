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


  <script src="{{ asset('js/profile.js') }}"></script>
</body>
</html>
