
@extends('layouts.custom')

@section('title', 'Home')

@section('content')

  <section class="container mx-auto px-4 sm:px-6 py-12 min-h-screen">
    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center mb-8 font-poppins">
      Announcement
    </h1>

    <!-- Filter Buttons -->
    <div class="flex flex-wrap gap-2 justify-center mb-8 max-w-[1400px] mx-auto">
      <button data-filter="All"
        class="filter-btn px-4 py-2 border border-[#521C0D] rounded-md bg-[#D5451B] text-white font-medium transition">
        All
      </button>
      <button data-filter="Enrollment"
        class="filter-btn px-4 py-2 border border-[#521C0D] rounded-md bg-white text-[#521C0D] transition">
        Enrollment
      </button>
      <button data-filter="Academics"
        class="filter-btn px-4 py-2 border border-[#521C0D] rounded-md bg-white text-[#521C0D] transition">
        Academics
      </button>
      <button data-filter="USG"
        class="filter-btn px-4 py-2 border border-[#521C0D] rounded-md bg-white text-[#521C0D] transition">
        USG
      </button>
      <button data-filter="Events"
        class="filter-btn px-4 py-2 border border-[#521C0D] rounded-md bg-white text-[#521C0D] transition">
        Events
      </button>
      <button data-filter="System"
        class="filter-btn px-4 py-2 border border-[#521C0D] rounded-md bg-white text-[#521C0D] transition">
        System
      </button>
      <button data-filter="Public"
        class="filter-btn px-4 py-2 border border-[#521C0D] rounded-md bg-white text-[#521C0D] transition">
        Public
      </button>
    </div>

    <!-- Announcement Cards Grid -->
    <div id="announcement-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-[1400px] mx-auto">
      
      <!-- Card Template -->
      <div data-category="Enrollment"
        class="announcement-card bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
        <h3 class="text-lg sm:text-xl font-bold mb-2 text-[#521C0D] flex items-center gap-2">
          <img src="{{asset('image/icon/enroolment.png')}}" alt="Enrollment Icon" class="w-9 h-9">
          Enrollment Schedule – 1st Semester 2025
        </h3>
        <p class="text-sm text-[#777777] mb-2">Posted: July 1, 2025 • Registrar's Office</p>
        <p class="text-sm text-[#1E1E1E] mb-3">
          Enrollment for 1st Sem SY 2025 begins on August 5. Submit clearance, COR, and payment slips through the portal.
        </p>
        <span class="inline-block bg-[#FF9B45] text-white text-xs px-3 py-1 rounded-md">Enrollment</span>
      </div>

      <div data-category="Academics"
        class="announcement-card bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
        <h3 class="text-lg sm:text-xl font-bold mb-2 text-[#521C0D] flex items-center gap-2">
          <img src="{{asset('image/icon/academic.png')}}" alt="Academics Icon" class="w-9 h-9">
          Academic Calendar Update
        </h3>
        <p class="text-sm text-[#777777] mb-2">Posted: July 2, 2025 • Registrar's Office</p>
        <p class="text-sm text-[#1E1E1E] mb-3">
          Updated school calendar now includes official holidays and midterm exam periods. Check the revised timeline now.
        </p>
        <span class="inline-block bg-[#FF9B45] text-white text-xs px-3 py-1 rounded-md">Academics</span>
      </div>

      <div data-category="USG"
        class="announcement-card bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
        <h3 class="text-lg sm:text-xl font-bold mb-2 text-[#521C0D] flex items-center gap-2">
          <img src="{{asset('image/icon/USG.png')}}" alt="USG Icon" class="w-9 h-9">
          USG Election Results 2025
        </h3>
        <p class="text-sm text-[#777777] mb-2">Posted: July 3, 2025 • Student Government</p>
        <p class="text-sm text-[#1E1E1E] mb-3">
          Congratulations to the newly elected USG officers! View full results and official statements from the election board.
        </p>
        <span class="inline-block bg-[#FF9B45] text-white text-xs px-3 py-1 rounded-md">USG</span>
      </div>

      <div data-category="Public"
        class="announcement-card bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
        <h3 class="text-lg sm:text-xl font-bold mb-2 text-[#521C0D] flex items-center gap-2">
          <img src="{{asset('image/icon/public.png')}}" alt="Public Icon" class="w-9 h-9">
          Campus Maintenance Notice
        </h3>
        <p class="text-sm text-[#777777] mb-2">Posted: July 4, 2025 • Registrar's Office</p>
        <p class="text-sm text-[#1E1E1E] mb-3">
          Electrical maintenance is scheduled on July 10–11. Expect temporary outages in admin and library buildings.
        </p>
        <span class="inline-block bg-[#FF9B45] text-white text-xs px-3 py-1 rounded-md">Public</span>
      </div>
    </div>
  </section>

 @endsection