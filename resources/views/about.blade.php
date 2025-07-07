
@extends('layouts.custom')

@section('title', 'Home')

@section('content')

  <!-- ABOUT PAGE -->
  <section class="bg-[#F4E7E1] text-[#521C0D] px-4 sm:px-6 py-20">
    <!-- Page Header -->
    <div class="max-w-[1400px] mx-auto text-center mb-12">
      <h1 class="text-3xl sm:text-4xl font-bold mb-4">What is PSU-GUIDE?</h1>
      <p class="text-base sm:text-lg">
        Your all-in-one information portal for PSU Quezon Campus — made by students, for students.
      </p>
    </div>

    <!-- Introduction Section -->
    <div class="max-w-3xl mx-auto mb-16 text-sm sm:text-base leading-relaxed">
      <p class="mb-4">
        <strong>PSU-GUIDE</strong> is a student-led capstone project developed to solve the growing problem of scattered,
        outdated, or unreliable announcements within Palawan State University Quezon Campus. With many students relying on
        various Facebook pages for updates, PSU-GUIDE creates a centralized and verified platform that combines all
        important information in one website.
      </p>
      <p class="mb-4">
        From enrollment schedules to campus events, student elections to class suspensions — everything you need to know
        is posted here after official verification. No more guesswork or misinformation.
      </p>
      <ul class="list-disc list-inside text-[#521C0D]">
        <li>Official updates directly from PSU departments like Registrar and USG</li>
        <li>Access through your PSU student number — no need to register</li>
        <li>Guest users can still view general announcements</li>
        <li>Ensures clarity, accuracy, and faster student communication</li>
      </ul>
    </div>

    <!-- Feature Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 max-w-[1400px] mx-auto mb-16">
      
      <!-- Verified Updates -->
      <div class="bg-white rounded-lg p-6 shadow-md hover:shadow-lg transition text-center">
        <img src="{{asset('image/icon/verified.png')}}" alt="Verified Icon" class="mx-auto mb-3 h-12 w-12">
        <h3 class="font-bold text-lg mb-2">Verified Updates</h3>
        <p class="text-sm">All posts are reviewed by PSU staff before publishing — no fake news, no confusion.</p>
      </div>

      <!-- Centralized Info -->
      <div class="bg-white rounded-lg p-6 shadow-md hover:shadow-lg transition text-center">
        <img src="{{asset('image/icon/web.png')}}" alt="Centralized Icon" class="mx-auto mb-3 h-12 w-12">
        <h3 class="font-bold text-lg mb-2">Centralized Access</h3>
        <p class="text-sm">Enrollment, USG, academics, memos — all accessible from one unified platform.</p>
      </div>

      <!-- Student Login -->
      <div class="bg-white rounded-lg p-6 shadow-md hover:shadow-lg transition text-center">
        <img src="{{asset('image/icon/login.png')}}" alt="Student Login Icon" class="mx-auto mb-3 h-12 w-12">
        <h3 class="font-bold text-lg mb-2">Student Access</h3>
        <p class="text-sm">Login is seamless with your PSU student number. No sign-ups required.</p>
      </div>

      <!-- Guest Access -->
      <div class="bg-white rounded-lg p-6 shadow-md hover:shadow-lg transition text-center">
        <img src="{{asset('image/icon/gues.png')}}" alt="Guest Icon" class="mx-auto mb-3 h-12 w-12">
        <h3 class="font-bold text-lg mb-2">Guest-Friendly</h3>
        <p class="text-sm">Non-PSU users can still access public updates such as events or notices.</p>
      </div>
    </div>

    <!-- CTA Footer -->
    <div class="container mx-auto px-4 sm:px-6 text-center max-w-[1400px]">
      <h2 class="text-2xl sm:text-3xl font-bold mb-4">Why PSU-GUIDE Matters</h2>
      <p class="text-sm sm:text-base max-w-2xl mx-auto mb-6">
        This platform is more than just a website — it's a solution. PSU-GUIDE bridges communication gaps, promotes transparency, and helps students stay informed and connected with what’s happening in our campus.
      </p>
      <a href="contact.html"
        class="inline-block bg-[#D5451B] text-white px-6 py-3 rounded-md font-bold hover:bg-[#FF9B45] transition">
        Learn More About This Project
      </a>
    </div>
  </section>
  
 @endsection