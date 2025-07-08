@extends('layouts.custom')

@section('content')
<section class="container mx-auto px-4 sm:px-6 py-12 min-h-screen">
  <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center mb-8 font-poppins">Announcement</h1>

  <!-- Filter Buttons -->
  <div class="flex flex-wrap gap-2 justify-center mb-8 max-w-[1400px] mx-auto">
    <button data-filter="All" class="filter-btn px-4 py-2 border border-[#521C0D] rounded-md bg-[#D5451B] text-white font-medium transition">All</button>
    @foreach ($categories as $category)
      <button data-filter="{{ $category->name }}"
        class="filter-btn px-4 py-2 border border-[#521C0D] rounded-md bg-white text-black transition">
        {{ $category->name }}
      </button>
    @endforeach
  </div>

  <!-- Announcement Cards Grid -->
  <div id="announcement-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-[1400px] mx-auto">
    @forelse ($announcements as $announcement)
      <div data-category="{{ $announcement->category->name }}"
        class="announcement-card bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
        <h3 class="text-lg sm:text-xl font-bold mb-2 text-black flex items-center gap-2">
          <img src="{{ asset('image/icon/' . strtolower($announcement->category->name) . '.png') }}"
               alt="{{ $announcement->category->name }} Icon" class="w-9 h-9">
          {{ $announcement->title }}
        </h3>
        <p class="text-sm text-[#777777] mb-2">
          Posted: {{ $announcement->created_at->format('F j, Y') }} • {{ ucfirst($announcement->user->role) }}
        </p>
        <p class="text-sm text-[#1E1E1E] mb-3">{{ $announcement->content }}</p>
        <span class="inline-block bg-[#FF9B45] text-white text-xs px-3 py-1 rounded-md">
          {{ $announcement->category->name }}
        </span>
      </div>
    @empty
      <p class="text-center text-gray-500 col-span-full">No announcements available.</p>
    @endforelse
  </div>
</section>
@endsection
