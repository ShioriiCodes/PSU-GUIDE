@extends('layouts.custom')

@section('title', 'Forgot Password')

@section('content')

<!-- Forgot Password Section -->
<section class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 py-12 px-4 sm:px-6 lg:px-8">
  <div class="max-w-md w-full space-y-8">
    <div class="text-center">
      <h2 class="text-3xl font-bold text-gray-900 mb-2">Forgot Your Password?</h2>
      <p class="text-gray-600">Enter your email below to reset your password.</p>
      <p class="text-sm text-gray-500 mt-2">
        Admins will receive a reset link. Other users will send a request to the admin.
      </p>
    </div>

    <div class="bg-white/95 backdrop-blur-sm p-8 rounded-2xl shadow-2xl border border-white/20">
      @if(session('status'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
          {{ session('status') }}
        </div>
      @endif

      <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-6">
          <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
          <input id="email" name="email" type="email" value="{{ old('email') }}"
                 required autofocus placeholder="e.g. 2025xxxxx@psu.palawan.edu.ph"
                 class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#D5451B] focus:border-transparent transition"
                 @error('email') is-invalid @enderror>
          @error('email')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="flex justify-center">
          <button type="submit"
                  class="w-full bg-gradient-to-r from-[#D5451B] to-[#FF9B45] text-white py-3 rounded-lg font-semibold hover:from-[#FF9B45] hover:to-[#D5451B] transition duration-300 transform hover:scale-105">
            Send Forgot Password Request
          </button>
        </div>
      </form>

      <div class="mt-6 text-center">
        <a href="{{ route('home') }}" class="text-[#D5451B] hover:text-[#FF9B45] hover:underline transition">
          Back to Login
        </a>
      </div>
    </div>
  </div>
</section>

@endsection
