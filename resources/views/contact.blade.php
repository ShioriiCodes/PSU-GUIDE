
@extends('layouts.custom')

@section('title', 'Home')

@section('content')
    
    <!-- CONTACT PAGE -->
    <section class="bg-[#F4E7E1] pt-10 pb-5 px-4 font-[Poppins]">
      <div class="max-w-[1200px] mx-auto flex flex-col lg:flex-row justify-between items-start gap-12 min-h-screen">

        <!-- Map and Contact Info (Left Side) -->
        <div class="w-full lg:w-1/2">
          <iframe 
            class="w-full h-64 sm:h-80 md:h-96 lg:h-[450px] rounded-lg shadow" 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3926.8096019448367!2d118.0007366!3d9.2299034!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x323526109fac9409%3A0xeea45881671330a3!2sPalawan%20State%20University%20-%20Quezon%20Campus!5e0!3m2!1sen!2sph!4v1720071234567!5m2!1sen!2sph" 
            allowfullscreen 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade">
          </iframe>

          <div class="text-black mt-6 space-y-4 text-sm sm:text-base">
            <div class="flex items-start gap-2">
              <img src="{{asset('image/icon/location.png')}}" alt="Location Icon" class="w-6 h-6 mt-1">
              <span><strong>Location:</strong> Palawan State University – Quezon Campus</span>
            </div>
            <div class="flex items-start gap-2">
              <img src="{{asset('image/icon/email.png')}}" alt="Email Icon" class="w-6 h-6 mt-1">
              <span><strong>Email:</strong> psuguide@gmail.com</span>
            </div>
            <div class="flex items-start gap-2">
              <img src="{{asset('image/icon/phone.png')}}" alt="Phone Icon" class="w-6 h-6 mt-1">
              <span><strong>Phone:</strong> +63 912 345 6789</span>
            </div>
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 mt-4">
              <a href="https://facebook.com/psuquezon" target="_blank" class="flex items-center gap-2 hover:underline">
                <img src="{{asset('image/icon/facebook.png')}}" alt="Facebook Icon" class="w-6 h-6"> Facebook
              </a>
              <a href="mailto:psuguide@gmail.com" class="flex items-center gap-2 hover:underline">
                <img src="{{asset('image/icon/gmail.png')}}" alt="Gmail Icon" class="w-6 h-6"> Email Us
              </a>
            </div>
          </div>
        </div>

        <!-- Contact Form (Right Side) -->
        <div class="w-full lg:w-[40%] max-w-md bg-white shadow-md rounded-lg p-6 mx-auto lg:mx-0">
          <div class="text-center mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-black mb-2">Contact Us</h1>
            <p class="text-black text-sm">Got questions or feedback? Reach out and we’ll get back to you.</p>
          </div>

          <form action="send-contact.php" method="POST" class="space-y-5">
            <div>
              <label class="block text-black font-semibold mb-1 text-sm">Full Name</label>
              <input type="text" name="name" required
                class="w-full px-3 py-2 border border-[#FF9B45] rounded-md bg-[#F4E7E1] text-black focus:outline-none focus:border-[#D5451B] text-sm">
            </div>
            <div>
              <label class="block text-black font-semibold mb-1 text-sm">Email Address</label>
              <input type="email" name="email" required
                class="w-full px-3 py-2 border border-[#FF9B45] rounded-md bg-[#F4E7E1] text-black focus:outline-none focus:border-[#D5451B] text-sm">
            </div>
            <div>
              <label class="block text-black font-semibold mb-1 text-sm">Message</label>
              <textarea name="message" rows="4" required
                class="w-full px-3 py-2 border border-[#FF9B45] rounded-md bg-[#F4E7E1] text-black focus:outline-none focus:border-[#D5451B] text-sm"></textarea>
            </div>

            <input type="hidden" name="_redirect" value="thanks.html">
            <div class="text-center">
              <button type="submit"
                class="bg-[#D5451B] text-white px-6 py-2 rounded-md hover:bg-[#FF9B45] transition text-sm">
                Send Message
              </button>
            </div>
          </form>

          <div class="text-center mt-8 text-xs text-[#521C0D]">
            <p>Email us directly at <a href="mailto:psuguide@gmail.com" class="underline text-[#D5451B]">psuguide@gmail.com</a></p>
          </div>
        </div>
      </div>
    </section>
    
 @endsection