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
      <div id="announcement-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @php $hasVisibleAnnouncements = false; @endphp
        @foreach ($announcements as $announcement)
          @php
            $categoryId = (int) ($announcement->category_id ?? 0);
            $categoryName = optional($announcement->category)->name;
            $normalizedName = strtolower(trim($categoryName ?? ''));
            $hasCustomCategory = filled($announcement->custom_category);
            $isPublicCategory = in_array($categoryId, [7]) || in_array($normalizedName, ['others', 'enrollment updates']);
            $shouldShowAnnouncement = auth()->check() || $hasCustomCategory || $isPublicCategory;
          @endphp
          @continue(!$shouldShowAnnouncement)
          @php $hasVisibleAnnouncements = true; @endphp
        <div class="announcement-card relative bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-2 cursor-pointer border border-gray-100"
          data-category="{{ $announcement->custom_category ?? optional($announcement->category)->name ?? 'Uncategorized' }}"
          @if($announcement->custom_category) data-custom-category="true" @endif
          onclick="openPostModal({{ $announcement->id }})">
            <!-- Card-level unread red dot -->
            <span id="ann-card-dot-{{ $announcement->id }}" class="hidden absolute top-3 right-3 w-3 h-3 rounded-full bg-red-600" title="New activity"></span>
            @php
                $imagePaths = [];
                $pdfAttachment = null;

                if ($announcement->poster_image) {
                    $posterRelative = str_contains($announcement->poster_image, '/')
                        ? ltrim($announcement->poster_image, '/')
                        : 'posters/' . $announcement->poster_image;
                    $posterExt = strtolower(pathinfo($posterRelative, PATHINFO_EXTENSION));
                    if (in_array($posterExt, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'])) {
                        $imagePaths[] = $posterRelative;
                    } elseif ($posterExt === 'pdf') {
                        $pdfAttachment = $posterRelative;
                    }
                }

                if ($announcement->images && $announcement->images->count()) {
                    foreach ($announcement->images as $image) {
                        $imagePaths[] = str_contains($image->path, '/')
                            ? ltrim($image->path, '/')
                            : 'posters/' . $image->path;
                    }
                }
            @endphp

            @if(count($imagePaths))
                <img src="{{ asset('storage/' . $imagePaths[0]) }}" alt="Announcement Image"
                     class="w-full h-48 object-cover rounded-xl mb-4">
            @elseif($pdfAttachment)
                <a href="{{ asset('storage/' . $pdfAttachment) }}" target="_blank" class="block text-center">
                    <img src="{{ asset('image/icon/pdf-(1).svg') }}"
                         alt="PDF File"
                         class="w-[200px] h-[200px] mx-auto mb-2">
                    <span class="text-sm text-gray-600 underline">View attached PDF</span>
                </a>
            @else
                <span class="text-gray-400 italic text-sm">No file uploaded</span>
            @endif

            @if($pdfAttachment && count($imagePaths))
                <a href="{{ asset('storage/' . $pdfAttachment) }}" target="_blank"
                   class="inline-flex items-center gap-2 text-xs text-[#D5451B] hover:text-[#FF9B45] underline transition mb-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11V3m0 0L8 7m4-4l4 4m6 5v6a2 2 0 01-2 2H8a2 2 0 01-2-2v-6" />
                    </svg>
                    View attached PDF
                </a>
            @endif

            <h2 class="text-xl font-bold flex items-center gap-2 text-gray-800 mb-2">
                @if($announcement->category)
                    {{-- <img src="{{ asset('image/icon/' . strtolower($announcement->category->name) . '.png') }}" class="w-12 h-12" /> --}}
                @endif
                {{ $announcement->title }}
                <button type="button"
                        class="inline-block w-2 h-2 rounded-full bg-red-600 align-middle hidden"
                        id="ann-dot-{{ $announcement->id }}"
                        title="New activity"
                        onclick="markAnnouncementNotificationsRead({{ $announcement->id }}, this); event.stopPropagation();"></button>
                @if($announcement->created_at->gt(now()->subDay()))
                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">New</span>
                @endif
            </h2>
          <p class="text-sm text-gray-500 mb-3">
            Posted: {{ $announcement->created_at->format('F j, Y') }} • {{ ucfirst($announcement->user->role) ?? 'Unknown' }}
          </p>

            <p class="text-gray-700 mb-4 overflow-hidden text-ellipsis break-words" style="max-height:6em; line-height:1.5em;">
              {!! autoLinkUrls($announcement->content) !!}
            </p>
          <span class="inline-block text-xs px-4 py-2 bg-[#FF9B45] text-white rounded-full mb-4">
            {{ $announcement->custom_category ?? optional($announcement->category)->name ?? 'Uncategorized' }}
          </span>

            {{-- Comment Count --}}
            <button onclick="openCommentsModal({{ $announcement->id }})"
              class="mt-4 block text-sm text-[#D5451B] hover:text-[#FF9B45] underline transition">
              {{ $announcement->comments->count() }} comment{{ $announcement->comments->count() !== 1 ? 's' : '' }}
            </button>
          </div>
        @endforeach
        @if (!$hasVisibleAnnouncements)
          <p class="text-center text-gray-500 col-span-full text-lg">No announcements found.</p>
        @endif
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
        <div class="sticky top-1 z-10 flex justify-end">
          <button type="button" aria-label="Close"
                  onclick="closePostModal()"
                  class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/90 text-gray-600 hover:text-white hover:bg-red-500 shadow ring-1 ring-gray-200 focus:outline-none focus:ring-2 focus:ring-red-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </button>
        </div>
        <div id="postModalContent">
          <p class="text-center text-gray-500">Loading...</p>
        </div>
      </div>
      <!-- Floating chat indicator inside modal -->
      <button id="postChatBtn" type="button" title="Go to comments"
              class="hidden z-[60] fixed bottom-6 right-6 w-12 h-12 rounded-full bg-red-600 text-white shadow-lg hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-black flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 3.866-4.03 7-9 7-1.22 0-2.38-.18-3.43-.5L3 20l1.5-3.5A6.5 6.5 0 013 12c0-3.866 4.03-7 9-7s9 3.134 9 7z" />
        </svg>
      </button>
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
            // Mark this announcement's notifications as read once opened via notification
            if (typeof markAnnouncementNotificationsRead === 'function' && announcementId) {
              try { markAnnouncementNotificationsRead(announcementId); } catch (_) {}
            }
          }, 1000); // wait for modal content to load
        });

    // Post chat indicator interactions
    (function(){
      const chatBtnEl = document.getElementById('postChatBtn');
      if (chatBtnEl) {
        chatBtnEl.addEventListener('click', function(){
          // Mark just this announcement as read, then hide and scroll
          if (typeof __currentAnnouncementId !== 'undefined' && __currentAnnouncementId) {
            markAnnouncementNotificationsRead(__currentAnnouncementId);
          }
          chatBtnEl.classList.add('hidden');
          const commentsStart = document.querySelector('#postModalContent #comments-start');
          if (commentsStart) commentsStart.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
      }
    })();
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

            if (filter === 'All') {
              card.style.display = 'block';
            } else if (filter === 'Others') {
              // Show both predefined "Others" category and any custom categories
              const hasCustomCategory = card.hasAttribute('data-custom-category');
              const isPredefinedOthers = category === 'Others';
              card.style.display = (isPredefinedOthers || hasCustomCategory) ? 'block' : 'none';
            } else {
              card.style.display = (category === filter) ? 'block' : 'none';
            }
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

      let __currentAnnouncementId = null;

      function openPostModal(id) {
      const modal = document.getElementById('postModal');
      const content = document.getElementById('postModalContent');
      __currentAnnouncementId = id;
      modal.classList.remove('hidden');
      content.innerHTML = '<p class="text-center text-gray-500">Loading...</p>';

      fetch(`/announcement/${id}/full`)
        .then(res => res.text())
        .then(html => {
          content.innerHTML = html;
          // After content loads, update chat indicator visibility (if available)
          if (typeof updateAnnouncementDots === 'function') {
            try { updateAnnouncementDots(); } catch (_) {}
          }
        });
    }

      function closePostModal() {
      document.getElementById('postModal').classList.add('hidden');
      window.location.replace('/announcement');
    }
    </script>

    @auth
    <script>
      // Show per-announcement red dots for unread notifications
      function updateAnnouncementDots() {
        fetch('/notifications/unread-by-announcement', { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
          .then(r => r.json())
          .then(data => {
            const items = (data && data.items) ? data.items : [];
            // First hide all dots
            document.querySelectorAll('[id^="ann-dot-"]').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('[id^="ann-card-dot-"]').forEach(el => el.classList.add('hidden'));
            // Show dots for announcements with unread
            items.forEach(item => {
              const dot = document.getElementById('ann-dot-' + item.announcement_id);
              if (dot) dot.classList.remove('hidden');
              const cardDot = document.getElementById('ann-card-dot-' + item.announcement_id);
              if (cardDot) cardDot.classList.remove('hidden');
            });
            // Toggle chat indicator in modal for current post
            const chatBtn = document.getElementById('postChatBtn');
            if (chatBtn) {
              const hasUnreadForCurrent = !!items.find(i => String(i.announcement_id) === String(__currentAnnouncementId));
              if (hasUnreadForCurrent) chatBtn.classList.remove('hidden'); else chatBtn.classList.add('hidden');
            }
          }).catch(() => {});
      }

      // Mark a single announcement's notifications as read
      function markAnnouncementNotificationsRead(announcementId, el) {
        fetch(`/notifications/read-announcement/${announcementId}` , {
          method: 'POST',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          }
        }).finally(() => {
          if (el) el.classList.add('hidden');
          // Refresh other dots and modal chat indicator
          updateAnnouncementDots();
        });
      }
      // Initial load and periodic refresh
      document.addEventListener('DOMContentLoaded', () => {
        updateAnnouncementDots();
        setInterval(updateAnnouncementDots, 30000);
        // Do not mark all as read automatically here to preserve per-announcement behavior
      });
    </script>
    @endauth

    <script>
    // Delegate AJAX for comments, replies, and likes inside the post modal
    document.addEventListener('click', function(e){
      const likeForm = e.target.closest('#postModalContent form[data-like-comment]');
      if (likeForm) {
        e.preventDefault();
        fetch(likeForm.action, {
          method: 'POST',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': likeForm.querySelector('[name=_token]').value
          }
        }).then(() => {
          // Mark per-announcement notifications as read after interaction
          const content = document.getElementById('postModalContent');
          const hiddenIdField = content?.querySelector('input[name="announcement_id"]');
          if (hiddenIdField && typeof markAnnouncementNotificationsRead === 'function') {
            try { markAnnouncementNotificationsRead(hiddenIdField.value); } catch (_) {}
          }
          reloadPostModalContent();
        });
      }
    });

    document.addEventListener('submit', function(e){
      const ajaxForm = e.target.closest('#postModalContent form[data-ajax-comment]');
      if (ajaxForm) {
        e.preventDefault();
        const formData = new FormData(ajaxForm);
        fetch(ajaxForm.action, {
          method: (ajaxForm.getAttribute('method') || 'POST').toUpperCase(),
          body: formData,
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).then(() => {
          // Mark per-announcement notifications as read after interaction
          const content = document.getElementById('postModalContent');
          const hiddenIdField = content?.querySelector('input[name="announcement_id"]');
          if (hiddenIdField && typeof markAnnouncementNotificationsRead === 'function') {
            try { markAnnouncementNotificationsRead(hiddenIdField.value); } catch (_) {}
          }
          reloadPostModalContent();
        });
      }
    });

    function reloadPostModalContent(){
      const content = document.getElementById('postModalContent');
      if (!content) return;
      const hiddenIdField = content.querySelector('input[name="announcement_id"]');
      if (!hiddenIdField) return;
      const announcementId = hiddenIdField.value;
      fetch(`/announcement/${announcementId}/full`).then(res => res.text()).then(html => { content.innerHTML = html; });
    }
    </script>

    <script>
    // Global lightbox for images inside the dynamically-loaded full post
    let __galleryImages = [];
    let __currentImageIndex = 0;
    let __keyHandlerBound = null;

    function getModalImages() {
      return Array.from(document.querySelectorAll('#postModalContent [data-lightbox-image]'));
    }

    function openImageLightboxFromElement(el) {
      const imgs = getModalImages();
      if (!imgs.length) return;
      __galleryImages = imgs.map(i => i.src);
      __currentImageIndex = Math.max(0, imgs.indexOf(el));

      const overlay = document.getElementById('imageLightboxOverlay');
      const imgEl = document.getElementById('imageLightboxImg');
      if (!overlay || !imgEl) return;
      imgEl.src = __galleryImages[__currentImageIndex];
      overlay.classList.remove('hidden');
      overlay.classList.add('flex');
      if (typeof overlay.focus === 'function') overlay.focus();
      enableLightboxKeyHandlers();
      setupOverlayInteractions(overlay);
    }

    function closeImageLightbox() {
      const overlay = document.getElementById('imageLightboxOverlay');
      if (!overlay) return;
      overlay.classList.add('hidden');
      overlay.classList.remove('flex');
      disableLightboxKeyHandlers();
    }

    function nextImage() {
      if (!__galleryImages.length) return;
      __currentImageIndex = (__currentImageIndex + 1) % __galleryImages.length;
      const imgEl = document.getElementById('imageLightboxImg');
      if (imgEl) imgEl.src = __galleryImages[__currentImageIndex];
    }

    function prevImage() {
      if (!__galleryImages.length) return;
      __currentImageIndex = (__currentImageIndex - 1 + __galleryImages.length) % __galleryImages.length;
      const imgEl = document.getElementById('imageLightboxImg');
      if (imgEl) imgEl.src = __galleryImages[__currentImageIndex];
    }

    function setupOverlayInteractions(overlay) {
      if (!overlay || overlay.__lightboxBound) return;
      overlay.__lightboxBound = true;
      overlay.addEventListener('click', function(e){
        if (e.target === overlay) closeImageLightbox();
      });
      let touchStartX = 0, touchEndX = 0;
      overlay.addEventListener('touchstart', function(e){
        if (!e.changedTouches || !e.changedTouches.length) return;
        touchStartX = e.changedTouches[0].screenX;
      }, { passive: true });
      overlay.addEventListener('touchend', function(e){
        if (!e.changedTouches || !e.changedTouches.length) return;
        touchEndX = e.changedTouches[0].screenX;
        const deltaX = touchEndX - touchStartX;
        if (Math.abs(deltaX) > 50) {
          if (deltaX < 0) nextImage(); else prevImage();
        }
      });
    }

    function enableLightboxKeyHandlers(){
      if (__keyHandlerBound) return;
      __keyHandlerBound = function(e){
        if (e.key === 'Escape') closeImageLightbox();
        else if (e.key === 'ArrowRight') nextImage();
        else if (e.key === 'ArrowLeft') prevImage();
      };
      document.addEventListener('keydown', __keyHandlerBound);
    }
    function disableLightboxKeyHandlers(){
      if (!__keyHandlerBound) return;
      document.removeEventListener('keydown', __keyHandlerBound);
      __keyHandlerBound = null;
    }
    </script>
@endsection
