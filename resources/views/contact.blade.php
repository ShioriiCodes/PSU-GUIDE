
@extends('layouts.custom')

@section('title', 'Contact')

@section('content')
    
<section class="bg-white pt-10 pb-5 px-4 font-[Poppins]">
      <div class="max-w-[1200px] mx-auto min-h-screen">
        <!-- Header Section -->
        <div class="text-center mb-12">
          <h1 class="text-3xl sm:text-4xl font-bold text-black mb-4">Get in Touch</h1>
          <p class="text-gray-700 text-base max-w-2xl mx-auto">Have questions or need assistance? We're here to help. Reach out via the form or contact details below.</p>
        </div>

        <div class="flex flex-col lg:flex-row justify-between items-start gap-12">

          <!-- Map and Contact Info (Left Side) -->
          <div class="w-full lg:w-1/2">
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
              <iframe 
                class="w-full h-64 sm:h-80 md:h-96 lg:h-[450px] rounded-lg shadow" 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3926.8096019448367!2d118.0007366!3d9.2299034!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x323526109fac9409%3A0xeea45881671330a3!2sPalawan%20State%20University%20-%20Quezon%20Campus!5e0!3m2!1sen!2sph!4v1720071234567!5m2!1sen!2sph" 
                allowfullscreen 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
              </iframe>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6 space-y-4">
              <h2 class="text-xl font-bold text-black mb-4">Contact Information</h2>
              <div class="space-y-3 text-sm sm:text-base">
                <div class="flex items-start gap-3">
                  <img src="{{asset('image/icon/location.png')}}" alt="Location Icon" class="w-7 h-7 mt-1 flex-shrink-0">
                  <span><strong>Location:</strong> Palawan State University – Quezon Campus</span>
                </div>
                <div class="flex items-start gap-3">
                  <img src="{{asset('image/icon/email.png')}}" alt="Email Icon" class="w-7 h-7 mt-1 flex-shrink-0">
                  <span><strong>Email:</strong> psuguide.info@gmail.com</span>
                </div>
                <div class="flex items-start gap-3">
                  <img src="{{asset('image/icon/phone.png')}}" alt="Phone Icon" class="w-7 h-7 mt-1 flex-shrink-0">
                  <span><strong>Phone:</strong> +63 912 345 6789</span>
                </div>
              </div>
              <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 mt-6">
                <a href="https://facebook.com/psuquezon" target="_blank" class="flex items-center gap-2 bg-[#D5451B] text-white px-4 py-2 rounded-lg hover:bg-[#FF9B45] transition duration-300 transform hover:scale-105">
                  <img src="{{asset('image/icon/facebook.png')}}" alt="Facebook Icon" class="w-6 h-6"> Facebook
                </a>
                <a href="mailto:psuguide.info@gmail.com" class="flex items-center gap-2 bg-[#D5451B] text-white px-4 py-2 rounded-lg hover:bg-[#FF9B45] transition duration-300 transform hover:scale-105">
                  <img src="{{asset('image/icon/gmail.png')}}" alt="Gmail Icon" class="w-6 h-6"> Email Us
                </a>
              </div>
            </div>
          </div>

          <!-- Contact Form (Right Side) -->
          <div class="w-full lg:w-[40%] max-w-md bg-white shadow-lg rounded-lg p-8 mx-auto lg:mx-0 border border-gray-100">
            @if (session('success'))
              <div class="bg-green-100 text-green-800 px-4 py-3 rounded-lg mb-6 text-sm border border-green-200">
                {{ session('success') }}
              </div>
            @endif
            <div class="text-center mb-8">
              <h2 class="text-2xl font-bold text-black mb-2">Send Us a Message</h2>
              <p class="text-gray-600 text-sm">We'd love to hear from you. Fill out the form below and we'll respond as soon as possible.</p>
            </div>

            <form action="{{ route('contact.send') }}" method="POST" class="space-y-5">
              @csrf
              <div>
                <label class="block text-black font-semibold mb-2 text-sm">Full Name</label>
                <input type="text" name="name" required
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white text-black focus:outline-none focus:ring-2 focus:ring-[#D5451B] focus:border-[#D5451B] transition text-sm">
              </div>
              <div>
                <label class="block text-black font-semibold mb-2 text-sm">Email Address</label>
                <input type="email" name="email" required
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white text-black focus:outline-none focus:ring-2 focus:ring-[#D5451B] focus:border-[#D5451B] transition text-sm">
              </div>
              <div>
                <label class="block text-black font-semibold mb-2 text-sm">Message</label>
                <textarea name="message" rows="5" required
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white text-black focus:outline-none focus:ring-2 focus:ring-[#D5451B] focus:border-[#D5451B] transition text-sm resize-none"></textarea>
              </div>

              <div class="text-center">
                <button type="submit"
                  class="w-full bg-gradient-to-r from-[#D5451B] to-[#FF9B45] text-white px-6 py-3 rounded-lg hover:from-[#FF9B45] hover:to-[#D5451B] transition duration-300 transform hover:scale-105 font-semibold text-sm">
                  Send Message
                </button>
              </div>
            </form>

            <div class="text-center mt-8 p-4 bg-gray-50 rounded-lg">
              <p class="text-xs text-gray-600">
                Prefer direct email? <a href="mailto:psuguide.info@gmail.com" class="text-[#D5451B] hover:text-[#FF9B45] underline transition">psuguide.info@gmail.com</a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
    
@endsection