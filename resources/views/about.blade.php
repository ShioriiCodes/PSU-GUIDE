
@extends('layouts.custom')

@section('title', 'About')

@section('content')

<!-- ABOUT PAGE -->
<section class="bg-white pt-10 pb-5 px-4 font-[Poppins]">
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
        <h2 class="text-2xl font-bold text-black mb-4">Our Mission</h2>
        <p class="text-gray-700 leading-relaxed">
          To bridge the gap between university administration and students by providing a reliable platform for sharing important information, fostering transparency, and enhancing campus engagement.
        </p>
      </div>

      <div class="bg-white p-8 rounded-lg shadow">
        <h2 class="text-2xl font-bold text-black mb-4">Our Vision</h2>
        <p class="text-gray-700 leading-relaxed">
          To become the go-to source for all PSU Quezon-related information, ensuring every student and staff member stays informed and connected.
        </p>
      </div>
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
    <div class="container pt-10 mx-auto px-4 sm:px-6 text-center max-w-[1400px]">
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
    </div>
  </section>
  
@endsection