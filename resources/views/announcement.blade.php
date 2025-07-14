@extends('layouts.custom')

@section('content')
<section class="container mx-auto px-4 sm:px-6 py-12 min-h-screen">
  <h1 class="text-2xl sm:text-3xl md:text-4xl text-black font-bold text-center mb-8 font-poppins">Announcement</h1>

  <!-- Filter Buttons -->
  <div class="flex flex-wrap gap-2 justify-center mb-8 max-w-[1400px] mx-auto">
    <button data-filter="All" class="filter-btn px-4 py-2 border border-[#521C0D] rounded-md bg-[#D5451B] text-white font-medium transition">All</button>

    @foreach ($categories as $category)
      @guest
        @if (in_array($category->name, ['Campus Events', 'Social Gatherings']))
          <button data-filter="{{ $category->name }}" class="filter-btn px-4 py-2 border border-[#521C0D] rounded-md bg-white text-black transition">
            {{ $category->name }}
          </button>
        @endif
      @else
        <button data-filter="{{ $category->name }}" class="filter-btn px-4 py-2 border border-[#521C0D] rounded-md bg-white text-black transition">
          {{ $category->name }}
        </button>
      @endguest
    @endforeach
  </div>

  <!-- Announcement Cards Grid -->
  <div id="announcement-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-[1400px] mx-auto">
    @forelse ($announcements as $announcement)
      <div data-category="{{ optional($announcement->category)->name ?? '—' }}"
        class="announcement-card bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
        <h3 class="text-lg sm:text-xl font-bold mb-2 text-black flex items-center gap-2">
          @if(optional($announcement->category)->name)
            <img src="{{ asset('image/icon/' . strtolower(optional($announcement->category)->name) . '.png') }}"
                alt="{{ optional($announcement->category)->name }} Icon" class="w-9 h-9">
          @else
            <img src="{{ asset('image/icon/default.png') }}" alt="Default Icon" class="w-9 h-9">
          @endif
          {{ $announcement->title }}
        </h3>
        <p class="text-sm text-[#777777] mb-2">
          Posted: {{ $announcement->created_at->format('F j, Y') }} • 
          {{ $announcement->user ? ucfirst($announcement->user->role) : 'Unknown User' }}
        </p>
        <p class="text-sm text-[#1E1E1E] mb-3 line-clamp-4">
          {{ Str::limit($announcement->content, 120) }}
        </p>
        <span class="inline-block bg-[#FF9B45] text-white text-xs px-3 py-1 rounded-md">
          {{ optional($announcement->category)->name ?? '—' }}
        </span>
      </div>
    @empty
      <p class="text-center text-gray-500 col-span-full">No announcements available.</p>
    @endforelse
  </div>
</section>

<!-- Filter Script -->
<script>
  document.querySelectorAll('.filter-btn').forEach(button => {
    button.addEventListener('click', () => {
      const filter = button.getAttribute('data-filter');
      document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('bg-[#D5451B]', 'text-white'));
      button.classList.add('bg-[#D5451B]', 'text-white');

      document.querySelectorAll('.announcement-card').forEach(card => {
        const category = card.getAttribute('data-category');
        card.style.display = (filter === 'All' || category === filter) ? 'block' : 'none';
      });
    });
  });
</script>
@endsection
