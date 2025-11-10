
@extends('layouts.custom')

@section('title', 'About')

@section('content')

<!-- ABOUT PAGE -->
<section class="bg-white pt-10 px-4 font-[Poppins]">
  <div class="max-w-[1200px] mx-auto">

    <!-- Header -->
    <div class="text-center mb-12">
      <h1 class="text-3xl sm:text-4xl font-bold text-black mb-4">About PSU-Guide</h1>
      <p class="text-gray-700 text-base max-w-2xl mx-auto">
        Empowering the PSU Quezon community with seamless access to official information and announcements.
      </p>
    </div>

    <!-- Features Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
      <div class="bg-white p-6 rounded-lg shadow text-center">
        <h3 class="text-lg font-semibold text-black mb-2">Timely Announcements</h3>
        <p class="text-gray-600 text-sm">Stay updated with the latest campus news and events.</p>
      </div>

      <div class="bg-white p-6 rounded-lg shadow text-center">
        <h3 class="text-lg font-semibold text-black mb-2">Official Information</h3>
        <p class="text-gray-600 text-sm">Access verified and approved content from university staff.</p>
      </div>

      <div class="bg-white p-6 rounded-lg shadow text-center">
        <h3 class="text-lg font-semibold text-black mb-2">Community Driven</h3>
        <p class="text-gray-600 text-sm">Built by students for the benefit of the entire campus.</p>
      </div>

      <div class="bg-white p-6 rounded-lg shadow text-center">
        <h3 class="text-lg font-semibold text-black mb-2">Easy Access</h3>
        <p class="text-gray-600 text-sm">Simple and intuitive interface for all users.</p>
      </div>

      <div class="bg-white p-6 rounded-lg shadow text-center">
        <h3 class="text-lg font-semibold text-black mb-2">User-Friendly</h3>
        <p class="text-gray-600 text-sm">Designed with simplicity in mind for effortless navigation.</p>
      </div>

      <div class="bg-white p-6 rounded-lg shadow text-center">
        <h3 class="text-lg font-semibold text-black mb-2">Secure</h3>
        <p class="text-gray-600 text-sm">Your data is protected with robust security measures.</p>
      </div>
    </div>

    <!-- Mission and Vision -->
    <div class="grid md:grid-cols-2 gap-12 mb-16">
      <div class="bg-white p-8 rounded-lg shadow">
        <h2 class="text-2xl font-bold text-black mb-4">Mission</h2>
        <p class="text-gray-700 leading-relaxed">
            Palawan State University is committed to upgrade people’s quality of life by providing education
            opportunities through excellent instruction, research and innovation, extension, production
            services, and transnational collaborations.
        </p>
      </div>

      <div class="bg-white p-8 rounded-lg shadow">
        <h2 class="text-2xl font-bold text-black mb-4">Vision</h2>
        <p class="text-gray-700 leading-relaxed">
            An internationally recognized university that provides relevant and innovative education
            and research for lifelong learning and sustainable development.
        </p>
      </div>
    </div>

    <!-- Campus Image Section -->
    <div class="mb-16">
      <div class="bg-white p-8 rounded-lg shadow">
        <h2 class="text-2xl font-bold text-black mb-4 text-center">Palawan State University Quezon Campus</h2>
        <div class="flex justify-center">
          <img
            src="{{ asset('image/PROPOSED_SITE DEVELOPMENT_PLAN_page-0001.jpg') }}"
            alt="Palawan State University Quezon Campus"
            class="w-full max-w-full h-auto rounded-lg shadow-md cursor-pointer hover:opacity-90 transition-opacity"
            style="max-width: 1950px; height: auto;"
            onclick="openImageModal(this.src)"
          />
        </div>
      </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-80 z-50 hidden items-center justify-center p-4" onclick="closeImageModal()">
      <button onclick="closeImageModal()" class="absolute top-4 right-6 text-white text-3xl leading-none hover:text-gray-300 transition" aria-label="Close">&times;</button>
      <img id="modalImage" src="" alt="Palawan State University Quezon Campus" class="max-w-[90vw] max-h-[90vh] object-contain rounded shadow-lg" onclick="event.stopPropagation()">
    </div>

    <!-- CTA Section -->
    <div class="text-center bg-white p-8 rounded-lg shadow">
      <h2 class="text-2xl font-bold text-black mb-4">Ready to Stay Informed?</h2>
      <p class="text-gray-700 mb-6">
        Join thousands of students who rely on PSU-Guide for accurate and timely updates.
      </p>
      <a href="{{ route('contact') }}" class="bg-[#D5451B] text-white px-6 py-2 rounded-md hover:bg-[#FF9B45] transition">
        Get in Touch
      </a>
    </div>

    <!-- CTA Footer -->
    {{-- <div class="container pt-10 mx-auto px-4 sm:px-6 text-center max-w-[1400px]">
      <h2 class="text-3xl font-bold mb-4 flex items-center justify-center gap-3">
        <svg class="w-8 h-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Why PSU-GUIDE Matters
      </h2>
      <p class="text-slate-600 text-sm sm:text-base max-w-2xl mx-auto mb-6">
        This platform is more than just a website — it's a solution. PSU-GUIDE bridges communication gaps, promotes transparency, and helps students stay informed and connected with what's happening in our campus.
      </p>
      <a href="{{ route('contact') }}"
        class="inline-flex items-center gap-2 bg-slate-600 text-white px-6 py-3 rounded-lg hover:bg-slate-700 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Learn More About This Project
      </a>
    </div> --}}
  </section>

  <script>
    function openImageModal(imageSrc) {
      const modal = document.getElementById('imageModal');
      const modalImage = document.getElementById('modalImage');
      modalImage.src = imageSrc;
      modal.classList.remove('hidden');
      modal.classList.add('flex');
      document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }

    function closeImageModal() {
      const modal = document.getElementById('imageModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
      document.body.style.overflow = ''; // Restore scrolling
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(event) {
      if (event.key === 'Escape') {
        closeImageModal();
      }
    });
  </script>

@endsection
