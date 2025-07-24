@extends('layouts.custom')

@section('content')
    <section class="container mx-auto px-4 sm:px-6 py-12 min-h-screen">
      <h1 class="text-3xl text-center font-bold mb-10">Announcements</h1>
      {{-- Search Bar --}}
      <form method="GET" action="{{ route('announcements.index') }}" class="max-w-2xl mx-auto mb-6 flex gap-2">
        <input
          type="text"
          name="search"
          value="{{ request('search') }}"
          placeholder="Search announcements..."
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400"
        />
        <button
          type="submit"
          class="px-4 py-2 bg-[#FF9B45] text-white rounded-md hover:bg-orange-500 transition"
        >
          Search
        </button>
      </form>

      {{-- Filter Buttons --}}
      <div class="flex flex-wrap gap-2 justify-center mb-8">
        <button data-filter="All" class="filter-btn px-4 py-2 bg-[#D5451B] border border-[#521C0D] text-white rounded">All</button>
        @foreach ($categories as $category)
          <button data-filter="{{ $category->name }}" class="filter-btn px-4 py-2 bg-white text-black border border-[#521C0D] rounded">
            {{ $category->name }}
          </button>
        @endforeach
      </div>

      {{-- Announcements Grid --}}
      <div id="announcement-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($announcements as $announcement)
        <div class="announcement-card bg-white p-6 rounded-xl shadow relative transform transition-transform hover:scale-105 cursor-pointer"
          data-category="{{ optional($announcement->category)->name }}"
          onclick="openPostModal({{ $announcement->id }})">
            @if($announcement->poster_image)
                <img src="{{ asset('storage/' . $announcement->poster_image) }}" class="w-full h-48 object-cover rounded mb-4">
            @endif
            <h2 class="text-xl font-bold flex items-center gap-2">
                @if($announcement->category)
                    {{-- <img src="{{ asset('image/icon/' . strtolower($announcement->category->name) . '.png') }}" class="w-12 h-12" />  --}}
                @endif
                {{ $announcement->title }}
            </h2>
          <p class="text-sm text-gray-600 mb-2">
            Posted: {{ $announcement->created_at->format('F j, Y') }} • {{ ucfirst($announcement->user->role) ?? 'Unknown' }}
          </p>

          <p class="text-gray-800 mb-3">{{ Str::limit($announcement->content, 120) }}</p>
          <span class="inline-block text-xs px-3 py-1 bg-[#FF9B45] text-white rounded">
            {{ optional($announcement->category)->name ?? 'Uncategorized' }}
          </span>

          {{-- Comment Count --}}
          <button onclick="openCommentsModal({{ $announcement->id }})"
            class="mt-4 block text-sm text-blue-600 underline">
            {{ $announcement->comments->count() }} comment{{ $announcement->comments->count() !== 1 ? 's' : '' }}
          </button>
        </div>
        @empty
        <p class="text-center text-gray-500 col-span-full">No announcements found.</p>
        @endforelse
      </div>
    </section>

    {{-- MODAL --}}
    <div id="commentModal" class="fixed inset-0 bg-black bg-opacity-60 hidden z-50 flex items-center justify-center">
      <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl h-[90vh] overflow-y-auto p-6 relative">
        <button onclick="closeModal()" class="absolute top-3 right-4 text-gray-600 text-xl hover:text-red-500">&times;</button>
        <div id="modalContent">
          <p class="text-center text-gray-500">Loading...</p>
        </div>
      </div>
    </div>

    <div id="postModal" class="fixed inset-0 bg-black bg-opacity-70 hidden z-50 flex items-center justify-center px-4">
      <div class="bg-white max-w-3xl w-full max-h-[90vh] rounded-xl overflow-y-auto p-6 relative">
        <button onclick="closePostModal()" class="absolute top-4 right-4 text-gray-500 hover:text-red-600 text-2xl font-bold">&times;</button>

        <div id="postModalContent">
          <p class="text-center text-gray-500">Loading...</p>
        </div>
      </div>
    </div>

    @if(request('modal') === 'comments' && request('announcement_id'))
      <script>
        window.addEventListener('DOMContentLoaded', () => {
          const announcementId = '{{ request("announcement_id") }}';
          const commentId = '{{ request("comment_id") }}';

          openCommentsModal(announcementId);

          // Optional: highlight comment after modal loads
          setTimeout(() => {
            if (commentId) {
              const target = document.getElementById('comment-' + commentId);
              if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
                target.classList.add('ring', 'ring-orange-400');
                setTimeout(() => target.classList.remove('ring', 'ring-orange-400'), 3000);
              }
            }
          }, 1000); // wait for modal content to load
        });
      </script>
    @endif

    {{-- JS --}}
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

      function openCommentsModal(announcementId) {
        const modal = document.getElementById('commentModal');
        const content = document.getElementById('modalContent');
        modal.classList.remove('hidden');
        content.innerHTML = 'Loading...';

        fetch(`/announcement/${announcementId}/comments`)
          .then(res => res.text())
          .then(html => {
            content.innerHTML = html;
          });
      }

      function closeModal() {
        document.getElementById('commentModal').classList.add('hidden');
      }

      function openPostModal(id) {
      const modal = document.getElementById('postModal');
      const content = document.getElementById('postModalContent');
      modal.classList.remove('hidden');
      content.innerHTML = '<p class="text-center text-gray-500">Loading...</p>';

      fetch(`/announcement/${id}/full`)
        .then(res => res.text())
        .then(html => {
          content.innerHTML = html;
        });
    }

      function closePostModal() {
      document.getElementById('postModal').classList.add('hidden');
      window.location.replace('/announcement');
    }
    </script>
@endsection
