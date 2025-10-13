@extends('layouts.custom')

@section('title', 'Reset Password')

@section('content')

<!-- Reset Password Section -->
<section class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 py-12 px-4 sm:px-6 lg:px-8">
  <div class="max-w-md w-full space-y-8">
    <div class="text-center">
      <h2 class="text-3xl font-bold text-gray-900 mb-2">Reset Your Password</h2>
      <p class="text-gray-600">Enter your new password below</p>
    </div>

    <div class="bg-white/95 backdrop-blur-sm p-8 rounded-2xl shadow-2xl border border-white/20">
      <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="mb-6">
          <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
          <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                 class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#D5451B] focus:border-transparent transition"
                 @error('email') is-invalid @enderror>
          @error('email')
            <p class="mt-2 text-sm text-red-600">{{ $errors->first('email') }}</p>
          @enderror
        </div>

        <!-- Password -->
        <div class="mb-6">
          <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
          <input id="password" name="password" type="password" required autocomplete="new-password"
                 class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#D5451B] focus:border-transparent transition"
                 @error('password') is-invalid @enderror>
          @error('password')
            <p class="mt-2 text-sm text-red-600">{{ $errors->first('password') }}</p>
          @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
          <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
          <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                 class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#D5451B] focus:border-transparent transition"
                 @error('password_confirmation') is-invalid @enderror>
          @error('password_confirmation')
            <p class="mt-2 text-sm text-red-600">{{ $errors->first('password_confirmation') }}</p>
          @enderror
        </div>

        <div class="flex justify-center">
          <button type="submit"
                  class="w-full bg-gradient-to-r from-[#D5451B] to-[#FF9B45] text-white py-3 rounded-lg font-semibold hover:from-[#FF9B45] hover:to-[#D5451B] transition duration-300 transform hover:scale-105">
            Reset Password
          </button>
        </div>
      </form>
    </div>
  </div>
</section>

@endsection
