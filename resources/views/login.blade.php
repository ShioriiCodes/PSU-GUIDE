<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PSU-GUIDE | Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&family=Roboto&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bg-[#F4E7E1] text-[#521C0D]">
    <!-- Login Page Section -->
    <section class="bg-[#F4E7E1] py-16 min-h-screen font-[Poppins] flex items-center justify-center">
      <div class="container mx-auto px-4 sm:px-6 max-w-md">
        <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg border border-[#FF9B45]">
          
          <!-- Title -->
          <h2 class="text-xl sm:text-2xl font-bold text-center text-[#D5451B] mb-6">
            Student Login
          </h2>
          <!-- Form -->
          <form onsubmit="event.preventDefault(); validateLogin();">
            
            <!-- Student Number -->
            <div class="mb-4">
              <label for="studentNumber" class="block text-sm font-semibold text-[#521C0D] mb-2">Student Number</label>
              <input type="text" id="studentNumber" placeholder="e.g. 2025-XXXXX"
                class="w-full px-4 py-2 border border-[#FF9B45] rounded-md bg-[#fbf6f4] text-[#521C0D] focus:outline-none focus:ring-2 focus:ring-[#D5451B] text-sm">
            </div>

            <!-- Password -->
            <div class="mb-6">
              <label for="password" class="block text-sm font-semibold text-[#521C0D] mb-2">Password</label>
              <div class="relative">
                <input type="password" id="password"
                  class="w-full px-4 py-2 border border-[#FF9B45] rounded-md bg-[#fbf6f4] text-[#521C0D] focus:outline-none focus:ring-2 focus:ring-[#D5451B] text-sm pr-10">
                <img src="/assets/image/icon/open-eye.png" id="toggleIcon" onclick="togglePassword()"
                  class="absolute top-1/2 right-3 transform -translate-y-1/2 h-4 w-auto cursor-pointer" alt="Toggle visibility">
              </div>
            </div>

            <!-- Error Message -->
            <div id="errorBox" class="text-sm text-red-600 mb-4 hidden">Error message here</div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row gap-3">
              <button type="submit"
                class="w-full bg-[#D5451B] text-white py-2 rounded-md font-bold hover:bg-[#FF9B45] transition text-sm">
                Log In
              </button>
              <a href="/" class="w-full">
                <button type="button"
                  class="w-full bg-gray-400 text-white py-2 rounded-md font-bold hover:bg-gray-600 transition text-sm">
                  Cancel
                </button>
              </a>
            </div>

            <!-- Info -->
            <p class="text-center text-sm text-[#521C0D] mt-4">
              Don't have access? Contact the registrar's office.
            </p>

            <!-- Remember/Forgot -->
            <div class="mt-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
              <label class="inline-flex items-center text-sm text-[#521C0D]">
                <input type="checkbox" class="form-checkbox h-4 w-4 text-[#D5451B]">
                <span class="ml-2">Remember me</span>
              </label>
              <a href="#" class="text-sm text-[#D5451B] hover:underline">Forgot password?</a>
            </div>
          </form>
        </div>
      </div>
    </section>
    <!-- Footer -->
    <footer class="bg-[#D5451B] text-white text-center px-4 py-6 text-sm sm:text-base">
      <p>&copy; 2025 PSU-GUIDE | Palawan State University Quezon Campus</p>
      <p>Contact: <a href="mailto:psuguide@gmail.com" class="underline hover:text-gray-200">psuguide@gmail.com</a></p>
    </footer>

  <script src="{{asset('js/script.js')}}"></script>

</body>
</html>
