
@extends('layouts.db')

@section('title', 'Profile Settings')

@section('content')
  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 font-poppins">
    <!-- Top Bar -->
    <div class="sticky top-0 z-30 bg-white/80 backdrop-blur supports-[backdrop-filter]:bg-white/60 border-b">
      <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <a href="{{ asset('/') }}" class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200" title="Back to Home">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
          </a>
          <h1 class="text-lg sm:text-xl font-semibold text-slate-800">Profile Settings</h1>
        </div>
        <button id="toggleSidebar" class="md:hidden px-3 py-2 rounded bg-slate-100 hover:bg-slate-200">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
        </button>
      </div>
    </div>

    @php
      $hiddenRoles = ['admin', 'registrar', 'usg'];
      $canManagePassword = in_array(auth()->user()->role, ['faculty', 'student']);
      $rawProfile = auth()->user()->profile_picture ?? null;
      $profilePath = $rawProfile ? 'profile_pictures/' . basename($rawProfile) : null;
      $defaultAvatar = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTezfOC3OmGvUnyrEpDPez1zyz-ibH_Hn7ZEctdJs3-IohUKEpT3mA5XuVfBpjhuRw16ws&usqp=CAU';
      $profileUrl = $profilePath ? asset('storage/' . $profilePath) : $defaultAvatar;
    @endphp

    <div class="max-w-6xl mx-auto px-4 py-6 md:py-10 flex gap-6">
      <!-- Sidebar -->
      <aside id="sidebar" class="md:w-64 w-72 md:sticky md:top-24 h-max md:h-[calc(100vh-8rem)] md:self-start bg-white rounded-xl shadow p-4 space-y-3 transform -translate-x-full md:translate-x-0 fixed md:static left-4 top-24 z-40 transition-transform duration-300">
        <div class="flex items-center gap-3 pb-3 border-b">
          <img src="{{ $profileUrl }}" class="w-10 h-10 rounded-full object-cover border" alt="Avatar">
          <div>
            <div class="text-sm font-semibold text-slate-800">{{ auth()->user()->name }}</div>
            <div class="text-xs text-slate-500 truncate max-w-[12rem]">{{ auth()->user()->email }}</div>
          </div>
        </div>
        <nav class="space-y-2" id="sidebarNav">
          <button id="tabBtnPersonal" class="w-full text-left px-3 py-2 rounded-lg bg-[#E17C5F] text-white" onclick="showTab('personal', this)">Personal Info</button>
          @if (in_array(auth()->user()->role, ['faculty', 'student']))
          <button id="tabBtnSecurity" class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-100" onclick="showTab('security', this)">Security</button>
          @endif
          {{-- <button class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-100" onclick="showTab('preferences', this)">Preferences</button> --}}
          <button id="tabBtnLegal" class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-100" onclick="showTab('legal', this)">Legal</button>
          <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t">
            @csrf
            <button type="submit" class="w-full text-left px-3 py-2 rounded-lg bg-red-50 text-red-700 hover:bg-red-100">Log Out</button>
          </form>
        </nav>
      </aside>

      <!-- Main -->
      <main class="flex-1 space-y-6">
        <!-- Profile Header Card -->
        <div class="bg-white rounded-2xl shadow p-6">
          <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
            <div class="relative">
              <img id="profilePreview" src="{{ $profileUrl }}" class="w-24 h-24 rounded-full object-cover border shadow" alt="Profile Picture">
              <label class="absolute -bottom-2 -right-2 inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs bg-[#E17C5F] text-white cursor-pointer shadow">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M5 20h14v-2H5v2zm3.17-9.83l2.83 2.83 7.07-7.07-2.83-2.83-7.07 7.07z"/></svg>
                Change
                <input type="file" name="profile_picture" accept="image/*" onchange="previewImage(event)" class="hidden" form="profileForm">
              </label>
            </div>
            <div class="flex-1">
              <div class="text-xl font-semibold text-slate-800">{{ auth()->user()->name }}</div>
              <div class="text-sm text-slate-500">{{ ucfirst(auth()->user()->role) }}</div>
            </div>
            <a href="mailto:{{ auth()->user()->email }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200">{{ auth()->user()->email }}</a>
          </div>
        </div>

        <!-- Personal Information Card -->
        <div id="personal" class="tab bg-white rounded-2xl shadow p-6">
          <h2 class="text-lg font-semibold text-slate-800 mb-4">Personal Information</h2>
          <form id="profileForm" action="{{ route('user.update') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            @method('PUT')
            <div>
              <label class="block text-sm font-medium text-slate-700">Name</label>
              <input type="text" name="name" required value="{{ auth()->user()->name }}" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#E17C5F]">
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700">Email</label>
              <input type="email" name="email" required value="{{ auth()->user()->email }}" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#E17C5F]">
            </div>
            @if (!in_array(auth()->user()->role, $hiddenRoles))
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700">Student Number</label>
                <input type="text" name="student_number" value="{{ auth()->user()->student_number ?? '' }}" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#E17C5F]">
              </div>
            @endif
            <div class="md:col-span-2 flex justify-end gap-3 pt-2">
              <button type="reset" class="px-4 py-2 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200">Reset</button>
              <button type="submit" class="px-5 py-2 rounded-lg bg-[#E17C5F] text-white hover:bg-[#c05e4d]">Save Changes</button>
            </div>
          </form>
          @if (session('success'))
            <div id="successMessage" class="mt-4 p-3 rounded-lg bg-green-100 text-green-800 border border-green-200 opacity-0 transition-opacity duration-700">{{ session('success') }}</div>
          @endif
        </div>

        @if ($canManagePassword)
        <!-- Security Card -->
        <div id="security" class="tab hidden bg-white rounded-2xl shadow p-6">
          <h2 class="text-lg font-semibold text-slate-800 mb-4">Security</h2>

          @if (session('success_password'))
            <div id="successPasswordMessage" class="mb-4 p-3 rounded-lg bg-green-100 text-green-800 border border-green-200 opacity-0 transition-opacity duration-700">
              {{ session('success_password') }}
            </div>
          @endif

          <form action="{{ route('user.updatePassword') }}" method="POST" class="space-y-4 max-w-xl">
            @csrf
            @method('PUT')

            <div>
              <label class="block text-sm font-medium text-slate-700" for="current_password">Current Password</label>
              <input type="password" name="current_password" id="current_password" required autocomplete="current-password" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#E17C5F]">
              @error('current_password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700" for="new_password">New Password</label>
              <input type="password" name="new_password" id="new_password" required minlength="8" autocomplete="new-password" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#E17C5F]">
              @error('new_password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700" for="new_password_confirmation">Confirm New Password</label>
              <input type="password" name="new_password_confirmation" id="new_password_confirmation" required autocomplete="new-password" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#E17C5F]">
            </div>

            <div class="flex justify-end gap-3 pt-2">
              <button type="reset" class="px-4 py-2 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200">Reset</button>
              <button type="submit" class="px-5 py-2 rounded-lg bg-[#E17C5F] text-white hover:bg-[#c05e4d]">Update Password</button>
            </div>
          </form>
        </div>
        @endif

        <!-- Legal Card -->
        <div id="legal" class="tab hidden">
          <div class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-xl font-semibold text-slate-800 mb-4">Legal</h2>
            <ul class="space-y-3 text-slate-700 list-disc pl-6">
              <li><strong>Personal Use Only:</strong> Redistribution, resale, or commercial use is prohibited without permission.</li>
              <li><strong>Private by Design:</strong> Only you can view your settings and data.</li>
              <li><strong>No Tracking:</strong> We do not track or index your content.</li>
              <li><strong>Secure Storage:</strong> Preferences are stored securely; no external sharing occurs.</li>
            </ul>
          </div>
        </div>
      </main>
    </div>

    <!-- Helper Scripts -->
    <script>
      // Sidebar toggle (mobile)
      document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('toggleSidebar');
        const aside = document.getElementById('sidebar');
        if (btn && aside) {
          btn.addEventListener('click', () => aside.classList.toggle('-translate-x-full'));
        }
        // Success message fade animation
        const message = document.getElementById('successMessage');
        if (message) {
          setTimeout(() => message.classList.add('opacity-100'), 100);
          setTimeout(() => message.classList.remove('opacity-100'), 5000);
        }

        const passwordMessage = document.getElementById('successPasswordMessage');
        if (passwordMessage) {
          setTimeout(() => passwordMessage.classList.add('opacity-100'), 100);
          setTimeout(() => passwordMessage.classList.remove('opacity-100'), 5000);
        }

        const securityBtn = document.getElementById('tabBtnSecurity');
        const personalBtn = document.getElementById('tabBtnPersonal');
        const shouldOpenSecurity = @json(session()->has('success_password') || $errors->has('current_password') || $errors->has('new_password') || $errors->has('new_password_confirmation'));
        if (shouldOpenSecurity && securityBtn) {
          showTab('security', securityBtn);
        } else if (personalBtn) {
          showTab('personal', personalBtn);
        }
      });

      // Tab switcher
      function showTab(id, btn) {
        document.querySelectorAll('.tab').forEach(el => el.classList.add('hidden'));
        const section = document.getElementById(id);
        if (section) section.classList.remove('hidden');
        document.querySelectorAll('#sidebarNav button').forEach(b => b.classList.remove('bg-[#E17C5F]', 'text-white'));
        if (btn) btn.classList.add('bg-[#E17C5F]', 'text-white');
      }

      // Image preview
      function previewImage(event) {
        const file = event?.target?.files?.[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
          const img = document.getElementById('profilePreview');
          if (img) img.src = e.target.result;
        };
        reader.readAsDataURL(file);
      }
      window.showTab = showTab;
      window.previewImage = previewImage;
    </script>
  </div>

@endsection
