
@extends('layouts.custom')

@section('title', 'Home')

@section('content')

<!-- Hero Section -->
<section class="relative h-[95vh] mt-[-50px] flex items-center bg-[url('{{ asset('image/bg_psu.png') }}')] bg-cover bg-center bg-no-repeat">

  <!-- Dark Overlay -->
  <div class="absolute inset-0 bg-black bg-opacity-70"></div>

  <div class="relative z-10 container mx-auto px-6 sm:px-10 max-w-[1400px] min-h-screen flex items-center">

    {{-- ===================== --}}
    {{-- Guest View: Login Form --}}
    {{-- ===================== --}}
    @guest
    <div class="grid md:grid-cols-2 gap-10 w-full items-center">
      
      <!-- Left: Login Form -->
      <div class="flex justify-center items-center">
        <div class="bg-white p-8 sm:p-10 rounded-xl shadow-xl border border-gray-200 w-full max-w-md">
          <div class="text-center mb-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">Welcome Back</h2>
            <p class="text-gray-600">Sign in to access your account</p>
          </div>
          <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Corporate Email -->
            <div class="mb-6">
              <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Corporate Email</label>
              <input type="email" id="email" name="email" placeholder="e.g. 2025xxxxx@psu.palawan.edu.ph"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#D5451B] focus:border-transparent transition"
                required autofocus>
              @error('email')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
              @enderror
            </div>

            <!-- Password -->
            <div class="mb-6">
              <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
              <div class="relative">
                <input type="password" id="password" name="password"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#D5451B] focus:border-transparent transition pr-10"
                  required>
                <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                  <img id="toggleIcon" src="{{ asset('image/icon/closed-eye.png') }}" alt="Toggle Password" class="w-auto h-4">
                </button>
                @error('password')
                  <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </div>
            </div>

            <!-- Buttons -->
            <div class="space-y-4">
              <button type="submit" id="loginButton"
                class="w-full bg-[#D5451B] text-white py-3 rounded-lg font-semibold hover:bg-[#FF9B45] transition duration-300 transform hover:scale-105">
                Log In
              </button>
            </div>

            <p class="text-center text-sm text-gray-600 mt-6">
              Don't have access? <a href="{{ route('password.request') }}" class="text-[#D5451B] hover:text-[#FF9B45] hover:underline">Contact the admin</a>.
            </p>
          </form>
        </div>
      </div>

      <!-- Right: Text + Logo -->
      <div class="flex flex-col justify-between h-full py-12 md:py-20">
        <div class="text-center md:text-left">
          <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-6 leading-tight">
            Your Official PSU Quezon Info Hub
          </h1>
          <p class="text-base sm:text-lg text-gray-200">
            Stay updated with accurate and approved campus announcements.
          </p>
        </div>
        <div class="flex justify-center md:justify-center mt-10 md:mt-0">
          <img src="{{ asset('logo/PSU_Logo.png') }}" alt="PSU Logo Campus"
            class="w-4/5 max-w-xs sm:max-w-sm md:max-w-md rounded-lg shadow-lg">
        </div>
      </div>
    </div>
    @endguest

    {{-- ===================== --}}
    {{-- Auth View: Original Hero --}}
    {{-- ===================== --}}
    @auth
    <div class="flex flex-col md:flex-row items-center justify-between w-full">
      
      <!-- Left: Text -->
      <div class="md:w-1/2 text-center md:text-left mb-12 md:mb-0">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-6 leading-tight">
          Your Official PSU Quezon Info Hub
        </h1>
        <p class="text-base sm:text-lg text-gray-200 mb-6">
          Stay updated with accurate and approved campus announcements.
        </p>
      </div>

      <!-- Right: Logo -->
      <div class="md:w-1/2 flex justify-center">
        <img src="{{ asset('logo/PSU_Logo.png') }}" alt="PSU Logo Campus"
          class="w-4/5 max-w-xs sm:max-w-sm md:max-w-md rounded-lg mt-[-40px] sm:mt-0 shadow-lg">
      </div>
    </div>
    @endauth

  </div>
</section>

<script>
function togglePassword() {
  const password = document.getElementById("password");
  const toggleIcon = document.getElementById("toggleIcon");
  if (password.type === "password") {
    password.type = "text";
    toggleIcon.src = "{{ asset('image/icon/closed-eye.png') }}";
  } else {
    password.type = "password";
    toggleIcon.src = "{{ asset('image/icon/open-eye.png') }}";
  }
}
</script>

<section class="container mx-auto px-4 sm:px-6 py-16 max-w-[1400px]">
  <div class="text-center mb-12">
    <h2 class="text-3xl font-bold text-black mb-4">
      Latest Announcements
    </h2>
    <p class="text-gray-600">Stay informed with the most recent updates from our campus.</p>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($latestAnnouncements as $announcement)
      @if(Auth::check() || in_array(optional($announcement->category)->name, ['Others', 'Enrollment Updates']))
        <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition duration-200 border border-gray-100">
          <h3 class="text-lg font-semibold mb-2 text-gray-800">{{ $announcement->title }}</h3>
          <p class="text-sm text-gray-500 mb-3">Posted on {{ $announcement->created_at->format('F j, Y') }}</p>
          <p class="text-gray-700 mb-4 overflow-hidden text-ellipsis break-words" style="max-height:6em; line-height:1.5em;">
            {{ $announcement->content }}
          </p>
          <span class="inline-block text-xs text-white bg-[#FF9B45] px-3 py-1 rounded-full">
            {{ $announcement->visibility ?? 'Public' }}
          </span>
        </div>
      @endif
    @empty
      <p class="text-center col-span-3 text-gray-500">No announcements available.</p>
    @endforelse
  </div>
</section>

<!-- About PSU-Guide CTA Section -->
<section class="bg-[#F4E7E1] py-20">
  <div class="container mx-auto px-4 sm:px-6 text-center max-w-[1400px]">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">
      What is PSU-Guide?
    </h2>
    <p class="text-gray-700 text-lg max-w-2xl mx-auto mb-8 leading-relaxed">
      A centralized platform made by students for students. PSU-Guide makes information accessible, official, and easy to find.
    </p>
    <a href="{{ route('contact') }}"
      class="bg-[#D5451B] text-white px-8 py-3 rounded-lg font-semibold hover:bg-[#FF9B45] transition duration-300 transform hover:scale-105">
      Learn More About This Project
    </a>
  </div>
</section>

@endsection