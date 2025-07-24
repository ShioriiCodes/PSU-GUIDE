
@extends('layouts.db')

@section('title', 'Profile Settings')

@section('content')
    
  <!-- Navbar -->
  <div class="min-h-screen flex flex-col md:flex-row font-poppins">
    <!-- Mobile toggle button -->
    <button id="toggleSidebar" class="md:hidden p-3 bg-white shadow fixed top-4 right-4 z-50 rounded">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>

    <!-- Sidebar -->
    <aside id="sidebar" class="md:w-64 w-full md:static fixed z-40 top-0 left-0 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out bg-white shadow-lg p-4 md:h-auto h-full space-y-4">
      <div class="flex items-center gap-4 mb-6">
        <a href="{{ asset('/') }}" class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-gray-200 hover:bg-gray-300" title="Back to Home">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </a>
        <h2 class="text-xl font-semibold">{{_('Settings')}}</h2>
      </div>
      <nav class="space-y-3" id="sidebarNav">
        <button class="block w-full text-left py-2 px-4 rounded bg-[#E17C5F] text-white" onclick="showTab('personal', this)">Personal Info</button>
        <button class="block w-full text-left py-2 px-4 rounded hover:bg-[#E17C5F]" onclick="showTab('security', this)">Security</button>
        <button class="block w-full text-left py-2 px-4 rounded hover:bg-[#E17C5F]" onclick="showTab('preferences', this)">Preferences</button>
        <button class="block w-full text-left py-2 px-4 rounded hover:bg-[#E17C5F]" onclick="showTab('legal', this)">Legal</button>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button 
            type="submit" 
            class="block w-full text-left py-2 px-4 rounded bg-red-200 text-white hover:bg-red-600 hover:text-white">
            Log Out
          </button>
        </form>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8">
      <!-- Personal Information Section -->
      <div id="personal" class="tab">
        <h2 class="text-2xl font-bold mb-6">Personal Information</h2>
        <!-- Profile Update Form -->
        <form action="{{ route('user.update') }}" method="POST" enctype="multipart/form-data"
              class="flex flex-col lg:flex-row gap-8 items-start max-w-5xl">
          @csrf
          @method('PUT')
          <!-- Left: Profile Picture -->
          <div class="flex-shrink-0 text-center">
            <img id="profilePreview"
                src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTezfOC3OmGvUnyrEpDPez1zyz-ibH_Hn7ZEctdJs3-IohUKEpT3mA5XuVfBpjhuRw16ws&usqp=CAU' }}"
                alt="Profile Picture"
                class="w-40 h-40 rounded-full object-cover border border-gray-400 shadow-md mx-auto" />

            <div class="mt-4">
              <label class="block text-sm font-medium mb-1">Change Profile Picture</label>
              <input type="file" name="profile_picture" accept="image/*" onchange="previewImage(event)"
                    class="text-sm border border-gray-300 rounded px-3 py-1 w-full" />
            </div>
          </div>
          <!-- Right: Input Fields -->
          <div class="flex-1 space-y-4">
            <!-- Name -->
            <div>
              <label class="block text-sm font-medium">{{ _('Name') }}</label>
              <input type="text" name="name" required
                    value="{{ auth()->user()->name }}"
                    class="w-full mt-1 p-2 border border-gray-800 rounded" />
            </div>
            <!-- Email -->
            <div>
              <label class="block text-sm font-medium">Email</label>
              <input type="email" name="email" required
                    value="{{ auth()->user()->email }}"
                    class="w-full mt-1 p-2 border border-gray-800 rounded" />
            </div>
            <!-- Student Number -->
              @php
                $hiddenRoles = ['admin', 'registrar', 'usg'];
              @endphp

              @if (!in_array(auth()->user()->role, $hiddenRoles))
                <!-- Student Number -->
                <div>
                  <label class="block text-sm font-medium">Student Number</label>
                  <input type="text" name="student_number"
                        value="{{ auth()->user()->student_number ?? '' }}"
                        class="w-full mt-1 p-2 border border-gray-800 rounded" />
                </div>
              @endif
            <!-- Submit Button -->
            <button type="submit"
                    class="mt-4 px-6 py-2 bg-[#D5451B] text-white rounded hover:bg-[#aa3715]">
              Save Changes
            </button>
          </div>
        </form>
                <!-- Success Message (fades in and out) -->
        @if (session('success'))
          <div id="successMessage"
              class="max-w-[500px] mb-6 p-4 text-green-800 bg-green-100 border border-green-300 rounded-lg shadow opacity-0 transition-opacity duration-1000">
            {{ session('success') }}
          </div>

          <script>
            document.addEventListener('DOMContentLoaded', function () {
              const message = document.getElementById('successMessage');
              if (message) {
                // Fade in
                setTimeout(() => {
                  message.classList.remove('opacity-0');
                  message.classList.add('opacity-100');
                }, 100);
                // Fade out after 5 seconds
                setTimeout(() => {
                  message.classList.remove('opacity-100');
                  message.classList.add('opacity-0');
                }, 5100);
              }
            });
          </script>
        @endif
      </div>

      <!-- Security Section -->
      <div id="security" class="tab hidden">
        <h2 class="text-2xl font-bold mb-4">Security Settings</h2>
          <form id="passwordForm" action="{{ route('user.updatePassword') }}" method="POST" class="space-y-6 max-w-xl">
            @csrf
            @method('PUT')

            @error('current_password')
              <div class="text-sm text-red-600">{{ $message }}</div>
            @enderror
            @error('new_password')
              <div class="text-sm text-red-600">{{ $message }}</div>
            @enderror

            <div>
              <label class="block text-sm font-medium mb-1">Current Password</label>
              <input type="password" name="current_password" class="w-full p-2 border border-gray-800 rounded" placeholder="Enter current password">
            </div>

            <div>
              <label class="block text-sm font-medium mb-1">New Password</label>
              <input type="password" name="new_password" class="w-full p-2 border border-gray-800 rounded" placeholder="Enter new password">
            </div>

            <div>
              <label class="block text-sm font-medium mb-1">Confirm New Password</label>
              <input type="password" name="new_password_confirmation" class="w-full p-2 border border-gray-800 rounded" placeholder="Confirm new password">
            </div>

            <button type="submit" class="px-4 py-2 bg-[#D5451B] text-white rounded hover:bg-[#aa3715]">Update Password</button>
          </form>
                              <!-- Success & Error messages -->
            @if (session('success_password'))
              <div class="p-4 text-green-800 bg-green-100 border border-green-300 rounded-lg shadow">
                {{ session('success_password') }}
              </div>
            @endif
            <script>
          document.addEventListener('DOMContentLoaded', function () {
            const msg = document.querySelector('.bg-green-100');
            if (msg) {
              setTimeout(() => msg.remove(), 5000);
            }
          });
        </script>
        </div>

      <!-- Preferences Section -->
        <div id="preferences" class="tab hidden">
          <h2 class="text-2xl font-bold mb-6">User Preferences</h2>

          @if (session('success_preferences'))
            <div id="preferencesMessage"
                class="mb-6 p-4 text-green-800 bg-green-100 border border-green-300 rounded-lg shadow opacity-0 transition-opacity duration-1000">
              {{ session('success_preferences') }}
            </div>

            <script>
              document.addEventListener('DOMContentLoaded', function () {
                const msg = document.getElementById('preferencesMessage');
                if (msg) {
                  setTimeout(() => msg.classList.remove('opacity-0'), 100);
                  setTimeout(() => msg.classList.add('opacity-0'), 5100);
                }
              });
            </script>
          @endif

          <form action="{{ route('user.preferences.update') }}" method="POST" class="space-y-6 max-w-xl">
            @csrf
            @method('PUT')
          <!-- Notifications -->
            <div>
              <label class="block text-sm font-medium mb-2">Notification Preference</label>
              <div class="text-sm text-gray-700">
                <label class="flex items-center">
                  <input type="checkbox" name="notifications[]" value="email" class="mr-2"
                    {{ in_array('email', old('notifications', auth()->user()->notification_settings ?? [])) ? 'checked' : '' }}>
                  Email Notifications
                </label>
              </div>
            </div>
            <button type="submit" class="mt-4 px-6 py-2 bg-[#D5451B] text-white rounded hover:bg-[#aa3715]">
              Save Preferences
            </button>
          </form>
        </div>

      <!-- Legal Section -->
      <div id="legal" class="tab hidden">
        <div class="max-w-5xl mx-auto p-6 bg-white rounded-md shadow-md py-[30px] px-[50px]">
          <h2 class="text-3xl font-semibold text-gray-900 mb-6">Legal</h2>
          
          <ul class="space-y-4 text-gray-700 list-disc pl-2">
            <li>
              <strong>Personal Use Only:</strong> 
              Redistribution, resale, or use in any commercial project (including client work, 
              monetized apps, or promotional content) is strictly prohibited without proper 
              licensing or permission from the original creator.
            </li>
            <li>
              <strong>Private by Design:</strong> Public profile views and browsing history are disabled. Only you can view your data.
            </li>
            <li>
              <strong>Private Rights:</strong> We are committed to protecting your privacy.  Your information is protected and used only in accordance with our privacy policy.
            </li>
            <li>
              <strong>No Tracking or Indexing:</strong> This platform does not collect analytics, perform tracking, or index user content.
            </li>
            <li>
              <strong>Secure & Local Storage:</strong> All your preferences are stored locally or with secure methods. No external data sharing occurs.
            </li>
            <li>
              <strong>Data & Security:</strong> We employ industry-standard security measures to protect your data from unauthorized access, use, or disclosure.
            </li>
            <li>
              <strong>Prohibited Activities:</strong>You agree not to use this service for any illegal or unauthorized activities, like spamming and hacking.
            </li>
            <li>
              <strong>Secure & Local Storage:</strong> All your preferences are stored locally or with secure methods. No external data sharing occurs.
            </li>
          </ul>
        </div>
      </div>

    </main>
      <!-- Placeholder tabs -->
      <div id="security" class="tab hidden"><h2 class="text-2xl font-bold">Security</h2></div>
      <div id="preferences" class="tab hidden"><h2 class="text-2xl font-bold">Preferences</h2></div>
      <div id="privacy" class="tab hidden"><h2 class="text-2xl font-bold">Privacy</h2></div>
      <div id="apps" class="tab hidden"><h2 class="text-2xl font-bold">Connected Apps</h2></div>
    </main>

  </div>

@endsection