
@extends('layouts.custom')

@section('title', 'Home')

@section('content')

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center bg-[url('/assets/image/PSU-BG.png')] bg-cover bg-center bg-no-repeat font-[Poppins]">
      <!-- Dark Overlay -->
      <div class="absolute inset-0 bg-[#F4E7E1]/60"></div>
      <!-- Content -->
      <div class="relative z-10 container mx-auto px-6 sm:px-10 flex flex-col md:flex-row items-center max-w-[1400px]">

        <!-- Left: Text + Buttons -->
        <div class="md:w-1/2 text-center md:text-left mb-12 md:mb-0">
          <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-[#521C0D] mb-6 leading-tight">
            Your Official PSU Quezon Info Hub
          </h1>
          <p class="text-base sm:text-lg text-[#521C0D] mb-6">
            Stay updated with accurate and approved campus announcements.
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
            <!-- Login Button -->
            <a href="login.html"
              class="bg-[#D5451B] text-white px-5 py-2.5 rounded-md font-bold hover:bg-[#FF9B45] flex items-center justify-center gap-2 transition">
              <img src="{{asset('image/icon/login1.png')}}" alt="Login Icon" class="w-8 h-8 sm:w-9 sm:h-9">
              Log In with Student Number
            </a>

            <!-- Guest Button -->
            <a href="announcement.html"
              class="bg-white border-2 border-[#D5451B] text-[#521C0D] px-5 py-2.5 rounded-md font-bold hover:bg-[#cbbab6] flex items-center justify-center gap-2 transition">
              <img src="{{asset('image/icon/guest.png')}}" alt="Guest Icon" class="w-8 h-8 sm:w-9 sm:h-9">
              Browse as Guest
            </a>
          </div>
        </div>

        <!-- Right: Logo -->
        <div class="md:w-1/2 flex justify-center">
          <img src="{{ asset('logo/PSU_Logo.png') }}" alt="PSU Logo Campus" class="w-4/5 max-w-xs sm:max-w-sm md:max-w-md rounded-lg">
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="container mx-auto px-4 sm:px-6 py-16 max-w-[1400px]">
      <h2 class="text-2xl sm:text-3xl font-bold text-center text-[#521C0D] mb-12">
        Why Use PSU-Guide?
      </h2>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 text-center">
        
        <!-- Feature Item -->
        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition">
          <div class="mb-4 flex justify-center">
            <img src="{{asset('image/icon/verified.png')}}" alt="Verified Icon" class="w-12 h-12 sm:w-14 sm:h-14">
          </div>
          <h3 class="text-lg sm:text-xl font-semibold mb-2">Verified Updates</h3>
          <p class="text-sm sm:text-base text-[#521C0D]">All info is officially reviewed before posting. No fake or misleading updates.</p>
        </div>

        <!-- Feature Item -->
        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition">
          <div class="mb-4 flex justify-center">
            <img src="{{asset('image/icon/link.png')}}" alt="Portal Icon" class="w-12 h-12 sm:w-14 sm:h-14">
          </div>
          <h3 class="text-lg sm:text-xl font-semibold mb-2">Centralized Portal</h3>
          <p class="text-sm sm:text-base text-[#521C0D]">Enrollment, USG, events — everything in one place. Goodbye scattered pages!</p>
        </div>

        <!-- Feature Item -->
        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition">
          <div class="mb-4 flex justify-center">
            <img src="{{asset('image/icon/student.png')}}" alt="Student Icon" class="w-12 h-12 sm:w-14 sm:h-14">
          </div>
          <h3 class="text-lg sm:text-xl font-semibold mb-2">Student Access</h3>
          <p class="text-sm sm:text-base text-[#521C0D]">Login securely using your PSU student corporate email. No need to create a new account.</p>
        </div>

        <!-- Feature Item -->
        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition">
          <div class="mb-4 flex justify-center">
            <img src="{{asset('image/icon/guest.png')}}" alt="Guest Icon" class="w-12 h-12 sm:w-14 sm:h-14">
          </div>
          <h3 class="text-lg sm:text-xl font-semibold mb-2">Guest Mode</h3>
          <p class="text-sm sm:text-base text-[#521C0D]">Visitors can still view public info without needing an account.</p>
        </div>

      </div>
    </section>

    <!-- Latest Announcements Section -->
    <section class="container mx-auto px-4 sm:px-6 py-16 max-w-[1400px]">
      <h2 class="text-2xl sm:text-3xl font-bold text-center text-[#521C0D] mb-10">
        Latest Announcements
      </h2>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        
        <!-- Announcement Card -->
        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition flex flex-col justify-between h-full">
          <div>
            <h3 class="text-lg sm:text-xl font-semibold mb-2">Enrollment Schedule 1st Sem 2025</h3>
            <p class="text-sm text-[#521C0D] mb-3">Posted on July 1, 2025</p>
            <p class="text-sm sm:text-base text-[#521C0D]">Enrollment opens August 5. Prepare your documents now.</p>
          </div>
          <span class="mt-6 inline-block text-xs text-white bg-[#FF9B45] px-3 py-1 rounded-md self-start">
            For Students Only
          </span>
        </div>

        <!-- Announcement Card -->
        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition flex flex-col justify-between h-full">
          <div>
            <h3 class="text-lg sm:text-xl font-semibold mb-2">USG Election Guidelines</h3>
            <p class="text-sm text-[#521C0D] mb-3">Posted on June 28, 2025</p>
            <p class="text-sm sm:text-base text-[#521C0D]">Candidate registration ends July 10. Full details inside.</p>
          </div>
          <span class="mt-6 inline-block text-xs text-white bg-[#FF9B45] px-3 py-1 rounded-md self-start">
            Public
          </span>
        </div>

        <!-- Announcement Card -->
        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition flex flex-col justify-between h-full">
          <div>
            <h3 class="text-lg sm:text-xl font-semibold mb-2">General Assembly: July 20</h3>
            <p class="text-sm text-[#521C0D] mb-3">Posted on June 25, 2025</p>
            <p class="text-sm sm:text-base text-[#521C0D]">Open to all students and faculty. Attendance required.</p>
          </div>
          <span class="mt-6 inline-block text-xs text-white bg-[#FF9B45] px-3 py-1 rounded-md self-start">
            For Students Only
          </span>
        </div>

      </div>
    </section>

    <!-- About PSU-Guide CTA Section -->
    <section class="bg-white py-16">
      <div class="container mx-auto px-4 sm:px-6 text-center max-w-[1400px]">
        <h2 class="text-2xl sm:text-3xl font-bold text-[#521C0D] mb-4">
          What is PSU-Guide?
        </h2>
        <p class="text-[#521C0D] text-sm sm:text-base max-w-2xl mx-auto mb-6">
          A centralized platform made by students for students. PSU-Guide makes information accessible, official, and easy to find.
        </p>
        <a href="contact.html"
          class="inline-block bg-[#D5451B] text-white px-6 py-3 rounded-md font-bold hover:bg-[#FF9B45] transition">
          Learn More About This Project
        </a>
      </div>
    </section>

 @endsection