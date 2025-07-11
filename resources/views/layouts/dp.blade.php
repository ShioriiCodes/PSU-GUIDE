<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User Profile Settings</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">\
      <link rel="icon" href="{{ asset('logo/logo.ico') }}" type="image/x-icon">
  <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="bg-[#F4E7E1] font-sans">


        @yield('content')


  <script src="{{ asset('js/profile.js') }}"></script>
</body>
</html>
