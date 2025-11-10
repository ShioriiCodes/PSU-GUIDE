
@extends('layouts.custom')

@section('title', 'Home')

@section('content')

<!-- Hero Section -->
<section class="relative min-h-screen flex items-center bg-[url('{{ asset('image/bg_psu.png') }}')] bg-cover bg-center bg-no-repeat overflow-x-hidden">

  <!-- Dark Overlay -->
  <div class="absolute inset-0 bg-black bg-opacity-70"></div>

  <div class="relative z-10 container mx-auto px-4 sm:px-6 lg:px-10 max-w-[1400px] min-h-screen flex items-center w-full overflow-x-hidden py-12 md:py-0">

    {{-- ===================== --}}
    {{-- Guest View: Login Form --}}
    {{-- ===================== --}}
    @guest
    <div class="grid md:grid-cols-2 gap-6 md:gap-10 w-full items-center overflow-x-hidden">

      <!-- Left: Login Form (Hidden on mobile, shown on desktop) -->
      <div id="loginFormContainer" class="hidden md:flex justify-center items-center w-full">
        <div class="bg-white p-6 sm:p-8 rounded-xl shadow-xl border border-gray-200 w-full max-w-md mt-8 sm:mt-[340px] lg:mt-[-90px]">
          <div class="text-center mb-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">Welcome Back</h2>
            <p class="text-gray-600">Sign in to access your account</p>
          </div>
          <form id="loginForm" method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Corporate Email -->
            <div class="mb-6">
              <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Corporate Email</label>
              <input type="email" id="email" name="email" placeholder="e.g. 2025xxxxx@psu.palawan.edu.ph"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#D5451B] focus:border-transparent transition"
                required autofocus>
              @error('email')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
              @enderror
            </div>

            <!-- Password -->
            <div class="mb-6">
              <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
              <div class="relative">
                <input type="password" id="password" name="password"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#D5451B] focus:border-transparent transition pr-10"
                  required>
                <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                  <img id="toggleIcon" src="{{ asset('image/icon/closed-eye.png') }}" alt="Toggle Password" class="w-auto h-4">
                </button>
                @error('password')
                  <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </div>
            </div>

            <!-- Terms and Agreements -->
            <div class="mb-6">
                <label class="inline-flex items-start gap-2 cursor-pointer select-none">
                    <input id="termsAgree" type="checkbox" class="mt-[2px] h-4 w-4 border-gray-300 rounded text-[#D5451B] focus:ring-[#D5451B]" required>
                        <span class="text-sm text-gray-700">I agree to the
                            <button type="button" class="text-[#D5451B] hover:text-[#FF9B45] underline" onclick="openTermsModal()">Terms and Agreements</button>
                        </span>
                </label>
                <p id="termsError" class="hidden text-sm text-red-600 mt-2">You must agree to the Terms and Agreements to continue.</p>
            </div>

            <!-- Buttons -->
            <div class="space-y-4">
              <button type="submit" id="loginButton"
                class="w-full bg-[#D5451B] text-white py-3 rounded-lg font-semibold hover:bg-[#FF9B45] transition duration-300 transform hover:scale-105">
                Log In
              </button>
            </div>

            <p class="text-center text-sm text-gray-600 mt-6">
              Don't have access? <a href="{{ route('password.request') }}" class="text-[#D5451B] hover:text-[#FF9B45] hover:underline">Contact the admin</a>.
            </p>
          </form>
        </div>
      </div>

      <!-- Right: Text + Logo (Desktop: right column, Mobile: full width with login button) -->
      <div id="logoContentContainer" class="flex flex-col justify-center items-center h-full py-8 md:py-12 lg:py-20 w-full overflow-x-hidden">
        <div class="text-center mb-6 md:mb-8">
          <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-[40px] font-bold text-white mb-4 md:mb-6 leading-tight break-words">
            Your Official PSU Quezon Info Hub
          </h1>
          <p class="text-sm sm:text-base md:text-lg text-gray-200 break-words px-4">
            Stay updated with accurate and approved campus announcements.
          </p>
        </div>
        <div class="flex flex-col items-center gap-6">
          <img src="{{ asset('logo/PSU_Logo.png') }}" alt="PSU Logo Campus"
            class="w-2/3 sm:w-3/5 max-w-xs sm:max-w-sm md:max-w-md rounded-lg shadow-lg">
          <!-- Login button only visible on mobile -->
          <button onclick="showLoginForm()" id="mobileLoginBtn"
            class="md:hidden bg-[#D5451B] text-white px-8 py-3 rounded-lg font-semibold hover:bg-[#FF9B45] transition duration-300 transform hover:scale-105 shadow-lg">
            Sign In
          </button>
        </div>
      </div>

      <!-- Mobile Login Form (Hidden by default, shown when login button clicked - replaces logo on mobile) -->
      <div id="mobileLoginFormContainer" class="hidden justify-center items-center w-full md:hidden">
        <div class="bg-white p-6 sm:p-8 rounded-xl shadow-xl border border-gray-200 w-full max-w-md">
          <div class="text-center mb-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">Welcome Back</h2>
            <p class="text-gray-600">Sign in to access your account</p>
          </div>
          <form id="mobileLoginForm" method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Corporate Email -->
            <div class="mb-6">
              <label for="mobileEmail" class="block text-sm font-medium text-gray-700 mb-2">Corporate Email</label>
              <input type="email" id="mobileEmail" name="email" placeholder="e.g. 2025xxxxx@psu.palawan.edu.ph"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#D5451B] focus:border-transparent transition"
                required autofocus>
              @error('email')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
              @enderror
            </div>

            <!-- Password -->
            <div class="mb-6">
              <label for="mobilePassword" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
              <div class="relative">
                <input type="password" id="mobilePassword" name="password"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#D5451B] focus:border-transparent transition pr-10"
                  required>
                <button type="button" onclick="toggleMobilePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                  <img id="mobileToggleIcon" src="{{ asset('image/icon/closed-eye.png') }}" alt="Toggle Password" class="w-auto h-4">
                </button>
                @error('password')
                  <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </div>
            </div>

            <!-- Terms and Agreements -->
            <div class="mb-6">
                <label class="inline-flex items-start gap-2 cursor-pointer select-none">
                    <input id="mobileTermsAgree" type="checkbox" class="mt-[2px] h-4 w-4 border-gray-300 rounded text-[#D5451B] focus:ring-[#D5451B]" required>
                        <span class="text-sm text-gray-700">I agree to the
                            <button type="button" class="text-[#D5451B] hover:text-[#FF9B45] underline" onclick="openTermsModal()">Terms and Agreements</button>
                        </span>
                </label>
                <p id="mobileTermsError" class="hidden text-sm text-red-600 mt-2">You must agree to the Terms and Agreements to continue.</p>
            </div>

            <!-- Buttons -->
            <div class="space-y-4">
              <button type="submit" id="mobileLoginButton"
                class="w-full bg-[#D5451B] text-white py-3 rounded-lg font-semibold hover:bg-[#FF9B45] transition duration-300 transform hover:scale-105">
                Log In
              </button>
              <button type="button" onclick="showLogoContent()"
                class="w-full bg-gray-500 text-white py-3 rounded-lg font-semibold hover:bg-gray-600 transition duration-300 transform hover:scale-105">
                Back
              </button>
            </div>

            <p class="text-center text-sm text-gray-600 mt-6">
              Don't have access? <a href="{{ route('password.request') }}" class="text-[#D5451B] hover:text-[#FF9B45] hover:underline">Contact the admin</a>.
            </p>
          </form>
        </div>
      </div>
    </div>
    @endguest

    {{-- ===================== --}}
    {{-- Auth View: Original Hero --}}
    {{-- ===================== --}}
    @auth
    <div class="flex flex-col md:flex-row items-center justify-between w-full">

      <!-- Left: Text -->
      <div class="md:w-1/2 text-center md:text-left mb-12 md:mb-0">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-6 leading-tight">
          Your Official PSU Quezon Info Hub
        </h1>
        <p class="text-base sm:text-lg text-gray-200 mb-6">
          Stay updated with accurate and approved campus announcements.
        </p>
      </div>

      <!-- Right: Logo -->
      <div class="md:w-1/2 flex justify-center">
        <img src="{{ asset('logo/PSU_Logo.png') }}" alt="PSU Logo Campus"
          class="w-4/5 max-w-xs sm:max-w-sm md:max-w-md rounded-lg mt-[-40px] sm:mt-0 shadow-lg">
      </div>
    </div>
    @endauth

  </div>
</section>

    <!-- Terms and Agreements Modal -->
    <div id="termsModal" class="fixed inset-0 bg-black bg-opacity-60 hidden z-[100]">
        <div class="min-h-screen w-full flex items-center justify-center px-4">
            <div class="bg-white w-full max-w-2xl rounded-2xl shadow-xl overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Terms and Agreements</h3>
                    {{-- <button type="button" class="text-gray-500 hover:text-gray-700 text-2xl leading-none" onclick="closeTermsModal()">&times;</button> --}}
                </div>
                <div class="p-6 max-h-[60vh] overflow-y-auto text-sm text-gray-700 space-y-4">
                    <p>The PSU-GUIDE is a comprehensive information portal designed to provide
                        students, faculty, and staff of Palawan State University Quezon Campus
                        with convenient access to relevant information and resources.</p>
                    <p>Use of the PSU-GUIDE is permitted for personal and non-commercial purposes
                        only. Users are expected to access and utilize the portal in a lawful,
                        respectful, and responsible manner. You are responsible for maintaining
                        the confidentiality of your login credentials and for promptly reporting
                        any security vulnerabilities or concerns to the portal administrators.</p>
                    <p>Users are strictly prohibited from transmitting or storing any unlawful,
                        threatening, libelous, or otherwise objectionable material through the
                        PSU-GUIDE. Additionally, any attempt to interfere with or disrupt the
                        operation of the portal, or to gain unauthorized access to the PSU-GUIDE
                        or its related systems, is not allowed.</p>
                    <p>Palawan State University Quezon Campus is committed to safeguarding the
                        personal data of its students, faculty, and staff. The PSU-GUIDE collects,
                        stores, and processes personal data in accordance with the Data Privacy Act
                        of 2012 and other applicable laws to ensure the protection of user information.</p>
                    <p>The University reserves the right to modify these Terms and Agreement at any
                        time without prior notice. Continued use of the PSU-GUIDE after any modifications
                        have been made will be considered as acceptance of the updated Terms and Agreement.</p>
                </div>
                <div class="px-6 py-4 border-t flex items-center justify-end gap-3">
                    {{-- <button type="button" class="px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100" onclick="closeTermsModal()">Close</button> --}}
                    <button type="button" class="px-4 py-2 rounded-lg bg-[#D5451B] text-white hover:bg-[#FF9B45] transition duration-300 transform hover:scale-105" style="cursor: point;" onclick="agreeAndClose()">I Understand</button>
                </div>
            </div>
        </div>
        {{-- <button type="button" class="absolute inset-0 w-full h-full" style="cursor: default;" onclick="closeTermsModal()" aria-hidden="true"></button> --}}
    </div>

    <script>
    // Mark intent to show toast after successful auth
        document.addEventListener('DOMContentLoaded', function() {
        // Desktop login form
        var lf = document.getElementById('loginForm');
        if (lf) {
            lf.addEventListener('submit', function(e) {
            // Require terms agreement
            var terms = document.getElementById('termsAgree');
            var error = document.getElementById('termsError');
            if (terms && !terms.checked) {
                if (error) { error.classList.remove('hidden'); }
                e.preventDefault();
                return false;
            } else if (error) {
                error.classList.add('hidden');
            }
            try { sessionStorage.setItem('justLoggedIn', '1'); } catch (e) {}
            });
        }

        // Mobile login form
        var mobileLf = document.getElementById('mobileLoginForm');
        if (mobileLf) {
            mobileLf.addEventListener('submit', function(e) {
            // Require terms agreement
            var terms = document.getElementById('mobileTermsAgree');
            var error = document.getElementById('mobileTermsError');
            if (terms && !terms.checked) {
                if (error) { error.classList.remove('hidden'); }
                e.preventDefault();
                return false;
            } else if (error) {
                error.classList.add('hidden');
            }
            try { sessionStorage.setItem('justLoggedIn', '1'); } catch (e) {}
            });
        }
        });

        function togglePassword() {
        const password = document.getElementById("password");
        const toggleIcon = document.getElementById("toggleIcon");
        if (password && toggleIcon) {
            if (password.type === "password") {
                password.type = "text";
                toggleIcon.src = "{{ asset('image/icon/open-eye.png') }}";
            } else {
                password.type = "password";
                toggleIcon.src = "{{ asset('image/icon/closed-eye.png') }}";
            }
        }
        }

        function toggleMobilePassword() {
        const password = document.getElementById("mobilePassword");
        const toggleIcon = document.getElementById("mobileToggleIcon");
        if (password && toggleIcon) {
            if (password.type === "password") {
                password.type = "text";
                toggleIcon.src = "{{ asset('image/icon/open-eye.png') }}";
            } else {
                password.type = "password";
                toggleIcon.src = "{{ asset('image/icon/closed-eye.png') }}";
            }
        }
        }

        function openTermsModal(){
        var m = document.getElementById('termsModal');
        if (m) { m.classList.remove('hidden'); }
        }
        function closeTermsModal(){
        var m = document.getElementById('termsModal');
        if (m) { m.classList.add('hidden'); }
        }
        function agreeAndClose(){
        var cb = document.getElementById('termsAgree');
        var mobileCb = document.getElementById('mobileTermsAgree');
        if (cb) { cb.checked = true; }
        if (mobileCb) { mobileCb.checked = true; }
        closeTermsModal();
        }

        // Toggle between logo content and login form (Mobile only)
        function showLoginForm() {
            // Only work on mobile (screen width < 768px)
            if (window.innerWidth >= 768) {
                return; // Don't toggle on desktop
            }
            var logoContainer = document.getElementById('logoContentContainer');
            var mobileLoginContainer = document.getElementById('mobileLoginFormContainer');
            if (logoContainer && mobileLoginContainer) {
                logoContainer.classList.add('hidden');
                mobileLoginContainer.classList.remove('hidden');
                mobileLoginContainer.classList.add('flex');
                // Focus on email input when form is shown
                setTimeout(function() {
                    var emailInput = document.getElementById('mobileEmail');
                    if (emailInput) { emailInput.focus(); }
                }, 100);
            }
        }

        function showLogoContent() {
            // Only work on mobile (screen width < 768px)
            if (window.innerWidth >= 768) {
                return; // Don't toggle on desktop
            }
            var logoContainer = document.getElementById('logoContentContainer');
            var mobileLoginContainer = document.getElementById('mobileLoginFormContainer');
            if (logoContainer && mobileLoginContainer) {
                mobileLoginContainer.classList.add('hidden');
                mobileLoginContainer.classList.remove('flex');
                logoContainer.classList.remove('hidden');
            }
        }
    </script>

    @auth
        <script>
        // Show success toast once after redirect when authenticated
        document.addEventListener('DOMContentLoaded', function() {
        try {
            if (sessionStorage.getItem('justLoggedIn') === '1') {
            sessionStorage.removeItem('justLoggedIn');
            showLoginToast(@json(auth()->user()->name));
            }
        } catch (e) {}
        });

        function showLoginToast(name) {
        var toast = document.createElement('div');
        toast.className = 'fixed top-5 right-5 z-[100] bg-green-600 text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-2 opacity-0 transition-opacity duration-300';
        toast.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>' +
                            '<span>Welcome back, ' + (name || 'User') + '! You\'re now logged in.</span>';
        document.body.appendChild(toast);
        requestAnimationFrame(function(){ toast.classList.remove('opacity-0'); toast.classList.add('opacity-100'); });
        setTimeout(function(){
            toast.classList.remove('opacity-100');
            toast.classList.add('opacity-0');
            setTimeout(function(){ toast.remove(); }, 300);
        }, 3500);
        }
        </script>
    @endauth

    <section class="container mx-auto px-4 sm:px-6 py-16 max-w-[1400px] w-full overflow-x-hidden">
        <div class="text-center mb-8 sm:mb-12">
            <h2 class="text-2xl sm:text-3xl font-bold text-black mb-3 sm:mb-4 break-words">
            Latest Announcements
            </h2>
            <p class="text-sm sm:text-base text-gray-600 break-words px-2">Stay informed with the most recent updates from our campus.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 w-full">
            @forelse($latestAnnouncements as $announcement)
                @if(Auth::check() || in_array(optional($announcement->category)->name, ['Others', 'Enrollment Updates']))
                    <div class="bg-white p-4 sm:p-6 rounded-xl shadow-md hover:shadow-lg transition duration-200 border border-gray-100 w-full overflow-hidden">
                    <h3 class="text-base sm:text-lg font-semibold mb-2 text-gray-800 break-words line-clamp-2">{{ $announcement->title }}</h3>
                    <p class="text-xs sm:text-sm text-gray-500 mb-3">Posted on {{ $announcement->created_at->format('F j, Y') }}</p>
                    <div class="text-sm sm:text-base text-gray-700 mb-4 overflow-hidden break-words" style="max-height:6em; line-height:1.5em; display:-webkit-box; -webkit-line-clamp:4; -webkit-box-orient:vertical;">
                        {!! autoLinkUrls($announcement->content) !!}
                    </div>
                    <span class="inline-block text-xs text-white bg-[#FF9B45] px-3 py-1 rounded-full break-words">
                        {{ $announcement->visibility ?? 'Public' }}
                    </span>
                    </div>
                @endif
            @empty
                <p class="text-center col-span-full text-gray-500 text-sm sm:text-base">No announcements available.</p>
            @endforelse
        </div>
    </section>

    <!-- About PSU-Guide CTA Section -->
    <section class="bg-[#F4E7E1] py-12 sm:py-20 w-full overflow-x-hidden">
        <div class="container mx-auto px-4 sm:px-6 text-center max-w-[1400px] w-full">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-4 sm:mb-6 break-words">
                What is PSU-Guide?
            </h2>
            <p class="text-gray-700 text-base sm:text-lg max-w-2xl mx-auto mb-6 sm:mb-8 leading-relaxed break-words px-2">
                A centralized platform made by students for students. PSU-Guide makes information accessible, official, and easy to find.
            </p>
            <a href="{{ route('contact') }}"
                class="inline-block bg-[#D5451B] text-white px-6 sm:px-8 py-2 sm:py-3 rounded-lg font-semibold hover:bg-[#FF9B45] transition duration-300 transform hover:scale-105 text-sm sm:text-base">
                Learn More About This Project
            </a>
        </div>
    </section>

@endsection
