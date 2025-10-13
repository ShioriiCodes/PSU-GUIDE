@extends('layouts.custom')

@section('title', 'Announcements')

@section('content')
    <section class="container mx-auto px-4 sm:px-6 py-12 min-h-screen font-[Poppins]">
      <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Campus Announcements</h1>
        <p class="text-gray-600 text-lg">Browse and search through all official campus announcements.</p>
      </div>

      {{-- Search Bar --}}
      <form method="GET" action="{{ route('announcement') }}" class="max-w-3xl mx-auto mb-8 flex gap-3 relative">
        <div class="relative flex-1">
          <input
            type="text"
            id="searchInput"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search announcements..."
            class="w-full px-5 py-3 border border-gray-300 rounded-xl bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#D5451B] focus:border-transparent transition shadow-sm"
          />
          <!-- Clear button inside input -->
          <button
            type="button"
            id="clearSearch"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-lg font-bold transition"
            title="Clear search and refresh page"
          >&times;</button>
        </div>

        <button
          type="submit"
          class="px-6 py-3 bg-gradient-to-r from-[#D5451B] to-[#FF9B45] text-white rounded-xl hover:from-[#FF9B45] hover:to-[#D5451B] transition duration-300 transform hover:scale-105 flex items-center gap-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          Search
        </button>
      </form>

      {{-- Responsive Filter Buttons --}}
      <div class="mb-12">
        {{-- Mobile: Dropdown toggle --}}
        <div class="sm:hidden mb-6 text-center">
          <button onclick="toggleFilterDropdown()" class="px-6 py-3 bg-[#D5451B] text-white border border-[#521C0D] rounded-xl flex items-center gap-2 mx-auto hover:bg-[#FF9B45] transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
            </svg>
            Filter Categories
          </button>
          <div id="mobileFilterMenu" class="mt-4 hidden flex flex-col items-center gap-3">
            <button data-filter="All" class="filter-btn px-6 py-3 bg-[#D5451B] border border-[#521C0D] text-white rounded-xl w-4/5 hover:bg-[#FF9B45]">
              All
            </button>
            @foreach ($categories as $category)
              <button data-filter="{{ $category->name }}" class="filter-btn px-6 py-3 bg-white text-gray-800 border border-[#521C0D] rounded-xl w-4/5 hover:bg-[#F4E7E1]">
                {{ $category->name }}
              </button>
            @endforeach
          </div>
        </div>

        {{-- Desktop: Always visible --}}
        <div class="hidden sm:flex flex-wrap gap-3 justify-center">
          <button data-filter="All" class="filter-btn px-6 py-3 bg-[#D5451B] border border-[#521C0D] text-white rounded-xl hover:bg-[#FF9B45] transition">
            All
          </button>
          @foreach ($categories as $category)
            <button data-filter="{{ $category->name }}" class="filter-btn px-6 py-3 bg-white text-gray-800 border border-[#521C0D] rounded-xl hover:bg-[#F4E7E1] transition">
              {{ $category->name }}
            </button>
          @endforeach
        </div>
      </div>

      {{-- JS to toggle mobile dropdown --}}
      <script>
        function toggleFilterDropdown() {
          const menu = document.getElementById('mobileFilterMenu');
          menu.classList.toggle('hidden');
        }
      </script>

      {{-- Announcements Grid --}}
      <div id="announcement-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        @forelse ($announcements as $announcement)
        <div class="announcement-card bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-2 cursor-pointer border border-gray-100"
          data-category="{{ optional($announcement->category)->name }}"
          onclick="openPostModal({{ $announcement->id }})">
            @if($announcement->poster_image)
                @php
                    $ext = strtolower(pathinfo($announcement->poster_image, PATHINFO_EXTENSION));
                @endphp

                @if(in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']))
                    <img src="{{ asset('storage/' . $announcement->poster_image) }}"
                        alt="Poster Image"
                        class="w-full h-48 object-cover rounded-xl mb-4">
                @elseif($ext === 'pdf')
                    <a href="{{ asset('storage/' . $announcement->poster_image) }}" target="_blank" class="block text-center">
                        <img src="{{ asset('image/icon/pdf-(1).svg') }}" 
                            alt="PDF File" 
                            class="w-[200px] h-[200px] mx-auto mb-2">
                    </a>
                @else
                    <span class="text-gray-400 italic text-sm">Unsupported file</span>
            @endif
            @else
                <span class="text-gray-400 italic text-sm">No file uploaded</span>
            @endif

            <h2 class="text-xl font-bold flex items-center gap-2 text-gray-800 mb-2">
                @if($announcement->category)
                    {{-- <img src="{{ asset('image/icon/' . strtolower($announcement->category->name) . '.png') }}" class="w-12 h-12" /> --}}
                @endif
                {{ $announcement->title }}
            </h2>
          <p class="text-sm text-gray-500 mb-3">
            Posted: {{ $announcement->created_at->format('F j, Y') }} • {{ ucfirst($announcement->user->role) ?? 'Unknown' }}
          </p>

            <p class="text-gray-700 mb-4 overflow-hidden text-ellipsis break-words" style="max-height:6em; line-height:1.5em;">
              <strong>{{ $announcement->content }}</strong>
            </p>
          <span class="inline-block text-xs px-4 py-2 bg-[#FF9B45] text-white rounded-full mb-4">
            {{ optional($announcement->category)->name ?? 'Uncategorized' }}
          </span>

            {{-- Comment Count --}}
            <button onclick="openCommentsModal({{ $announcement->id }})"
              class="mt-4 block text-sm text-[#D5451B] hover:text-[#FF9B45] underline transition">
              {{ $announcement->comments->count() }} comment{{ $announcement->comments->count() !== 1 ? 's' : '' }}
            </button>
          </div>
        @empty
          <p class="text-center text-gray-500 col-span-full text-lg">No announcements found.</p>
        @endforelse
      </div>
    </section>

    {{-- Floating Notification Popup --}}
  <div id="notificationPopup" class="fixed top-20 right-4 bg-white border border-gray-200 rounded-lg shadow-lg p-4 max-w-sm hidden z-50">
    <div class="flex items-center gap-3">
      <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <div class="flex-1">
        <p class="text-sm font-semibold text-gray-800" id="notificationTitle">New Reply</p>
        <p class="text-xs text-gray-600" id="notificationMessage">Someone replied to your comment.</p>
      </div>
      <button onclick="closeNotification()" class="text-gray-400 hover:text-gray-600">
        &times;
      </button>
    </div>
  </div>

  <script>
    function showNotification(title, message) {
      document.getElementById('notificationTitle').textContent = title;
      document.getElementById('notificationMessage').textContent = message;
      document.getElementById('notificationPopup').classList.remove('hidden');
      setTimeout(closeNotification, 5000);
    }

    function closeNotification() {
      document.getElementById('notificationPopup').classList.add('hidden');
    }
  </script>

    {{-- MODAL --}}
    <div id="commentModal" class="fixed inset-0 bg-black bg-opacity-60 hidden z-50 flex items-center justify-center">
      <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl h-[90vh] overflow-y-auto p-6 relative">
        <button onclick="closeModal()" class="absolute top-3 right-4 text-gray-600 text-xl hover:text-red-500">&times;</button>
        <div id="modalContent">
          <p class="text-center text-gray-500">Loading...</p>
        </div>
      </div>
    </div>

    <div id="postModal" class="fixed inset-0 bg-black bg-opacity-70 hidden z-50 flex items-center justify-center px-4">
      <div class="bg-white max-w-3xl w-full max-h-[90vh] rounded-lg overflow-y-auto p-6 relative">
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
      // Clear search and refresh page
      document.getElementById('clearSearch').addEventListener('click', function() {
        window.location.href = window.location.pathname;
      });

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
