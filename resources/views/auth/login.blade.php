@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <!-- Login Page Section -->
    <section class="bg-[#101010] py-16 min-h-screen font-[Poppins] flex items-center justify-center">
      <div class="container mx-auto px-4 sm:px-6 max-w-md">
        <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg border border-[#fec08a]">
          
          <!-- Title -->
          <h2 class="text-xl sm:text-2xl font-bold text-center text-black mb-6">
            Login
          </h2>
          <!-- Form -->
          <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <!-- Corporate Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm text-black mb-2">Corporate Email</label>
                <input type="email" id="email" name="email" placeholder="e.g. 2025xxxxx@psu.palawan.edu.ph"
                    class="w-full px-4 py-2 border border-black rounded-md bg-[#fbf6f4] text-black focus:outline-none focus:ring-1 focus:ring-black text-sm"
                    required autofocus>
                @error('email')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-6">
              <label for="password" class="block text-sm text-black mb-2">Password</label>
              <div class="relative">
                <input type="password" id="password" name="password"
                    class="w-full px-4 py-2 border border-black rounded-md bg-[#fbf6f4] text-black focus:outline-none focus:ring-1 focus:ring-black text-sm pr-10"
                    required>
                <img src="{{ asset('/image/icon/open-eye.png') }}" id="toggleIcon" onclick="togglePassword()"
                  class="absolute top-1/2 right-3 transform -translate-y-1/2 h-4 w-auto cursor-pointer" alt="Toggle visibility">
                    @error('password')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
              </div>
                <!-- Forgot Password -->
                <div class="mt-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                <a href="#" class="text-sm text-black hover:underline">Forgot password?</a>
                </div>
            </div>

            <!-- Error Message -->
            <div id="errorBox" class="text-sm text-red-600 mb-4 hidden">Error message here</div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row gap-3">
              <button type="submit"
                class="w-full bg-[#D5451B] text-white py-2 rounded-md font-bold hover:bg-[#FF9B45] transition text-sm">
                Log In
              </button>
              <a href="/" class="w-full">
                <button type="button"
                  class="w-full bg-gray-400 text-white py-2 rounded-md font-bold hover:bg-gray-600 transition text-sm">
                  Cancel
                </button>
              </a>
            </div>

            <!-- Info -->
            <p class="text-center text-sm text-black mt-4">
              Don't have access? Contact the registrar's office.
            </p>
          </form>
        </div>
      </div>
    </section>

</section>
@endsection
