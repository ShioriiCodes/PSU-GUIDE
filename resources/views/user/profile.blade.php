<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User Profile Settings</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#F4E7E1] font-sans">
  <!-- Navbar -->
  <div class="min-h-screen flex font-poppins">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-lg p-4 font-poppins">
      <div class="flex items-center gap-4 mb-6">
        <a href="/index.html" class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-gray-200 hover:bg-gray-300" title="Back to Home">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </a>
        <h2 class="text-xl font-semibold">Settings</h2>
      </div>
      <nav class="space-y-3" id="sidebarNav">
        <button class="block w-full text-left py-2 px-4 rounded hover:bg-[#E17C5F]" onclick="showTab('personal', this)">Personal Info</button>
        <button class="block w-full text-left py-2 px-4 rounded hover:bg-[#E17C5F]" onclick="showTab('security', this)">Security</button>
        <button class="block w-full text-left py-2 px-4 rounded hover:bg-[#E17C5F]" onclick="showTab('preferences', this)">Preferences</button>
        <button class="block w-full text-left py-2 px-4 rounded hover:bg-[#E17C5F]" onclick="showTab('privacy', this)">Privacy</button>
        <button class="block w-full text-left py-2 px-4 rounded text-red-500 hover:bg-red-500 hover:text-white" onclick="showTab('logout', this)">Logout</button>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8">
      <div id="personal" class="tab">
        <h2 class="text-2xl font-bold mb-4">Personal Information</h2>
        <form class="space-y-4 max-w-xl">
          <div class="flex items-center space-x-4">
            <img id="profilePreview" src="https://via.placeholder.com/80" alt="Profile Picture" class="w-20 h-20 rounded-full object-cover border" />
            <div>
              <label class="block text-sm font-medium">Change Profile Picture</label>
              <input type="file" accept="image/*" class="mt-1" onchange="previewImage(event)">
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium">Name</label>
            <input type="text" class="w-full mt-1 p-2 border border-gray-800 rounded" placeholder="Name">
          </div>
          <div>
            <label class="block text-sm font-medium">Username</label>
            <input type="text" class="w-full mt-1 p-2 border border-gray-800 rounded" placeholder="Username">
          </div>
          <div>
            <label class="block text-sm font-medium">Email</label>
            <input type="email" class="w-full mt-1 p-2 border border-gray-800 rounded" placeholder="2025xxxx@psu.palawan.edu.ph">
          </div>
          <div>
            <label class="block text-sm font-medium">Phone</label>
            <input type="tel" class="w-full mt-1 p-2 border border-gray-800 rounded" placeholder="+643123456789">
          </div>
          <div>
            <label class="block text-sm font-medium">Date of Birth</label>
            <input type="date" class="w-full mt-1 p-2 border border-gray-800 rounded">
          </div>
          <button type="submit" class="mt-4 px-4 py-2 bg-[#D5451B] text-white rounded hover:bg-[#aa3715]">Save Changes</button>
        </form>
      </div> 
      
      <!-- Security Section -->
      <div id="security" class="tab hidden">
        <h2 class="text-2xl font-bold mb-4">Security Settings</h2>
        <form id="passwordForm" class="space-y-6 max-w-xl">
          <div>
            <label class="block text-sm font-medium mb-1">Current Password</label>
            <input type="password" id="currentPassword" class="w-full p-2 border border-gray-800 rounded" placeholder="Enter current password">
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">New Password</label>
            <input type="password" id="newPassword" class="w-full p-2 border border-gray-800 rounded" placeholder="Enter new password">
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Confirm New Password</label>
            <input type="password" id="confirmPassword" class="w-full p-2 border border-gray-800 rounded" placeholder="Confirm new password">
          </div>
          <button type="submit" class="px-4 py-2 bg-[#D5451B] text-white rounded hover:bg-[#aa3715]">Update Password</button>
          <p id="passwordMessage" class="mt-2 text-sm"></p>
        </form>

        <!-- Two-Factor Authentication -->
        <div class="mt-10 border-t pt-8">
          <h3 class="text-xl font-semibold mb-4">Two-Factor Authentication (2FA)</h3>
          <p class="mb-4 text-gray-700">Add an extra layer of security to your account by enabling two-factor authentication.</p>
          <form class="space-y-4 max-w-xl">
            <div class="flex items-center">
              <input id="enable2fa" type="checkbox" class="mr-2">
              <label for="enable2fa" class="text-sm font-medium">Enable Two-Factor Authentication</label>
            </div>
            <div id="2fa-setup" class="hidden mt-4">
              <label class="block text-sm font-medium mb-1">Enter verification code</label>
              <input type="text" class="w-full p-2 border border-gray-800 rounded" placeholder="6-digit code">
              <button type="button" class="mt-2 px-4 py-2 bg-[#D5451B] text-white rounded hover:bg-[#aa3715]">Verify Code</button>
              <p class="text-xs text-gray-500 mt-2">Scan the QR code with your authenticator app or enter the code sent to your email.</p>
            </div>
          </form>
        </div>
      </div>
      
      <!-- Preferences Section -->
      <div id="preferences" class="tab hidden">
        <h2 class="text-2xl font-bold mb-4">Preferences</h2>
        <form class="space-y-6 max-w-xl">
          <div>
            <label class="block text-sm font-medium mb-1">Theme</label>
            <select class="w-full p-2 border border-gray-800 rounded">
              <option>Light</option>
              <option>Dark</option>
              <option>System Default</option>
            </select>
          </div>
          <div class="flex items-center">
            <input type="checkbox" id="emailNotifications" class="mr-2">
            <label for="emailNotifications" class="text-sm font-medium">Enable Email Notifications</label>
          </div>
          <div class="flex items-center">
            <input type="checkbox" id="smsNotifications" class="mr-2">
            <label for="smsNotifications" class="text-sm font-medium">Enable SMS Notifications</label>
          </div>
          <button type="submit" class="px-4 py-2 bg-[#D5451B] text-white rounded hover:bg-[#aa3715]">Save Preferences</button>
        </form>
      </div>

      <!-- Privacy Section -->
      <div id="privacy" class="tab hidden">
        <h2 class="text-2xl font-bold mb-4">Privacy Settings</h2>
        <div class="max-w-xl space-y-4 text-gray-800">
          <p>This account is for your personal use only. Your information is not shared or visible to other users.</p>
          <p>This website does not store history or allow public profile viewing. You are the only one with access to your data.</p>
          <p>No tracking, indexing, or data sharing occurs. All settings and information are stored locally or securely.</p>
        </div>
      </div>

    </main>
      <!-- Placeholder tabs -->
      <div id="security" class="tab hidden"><h2 class="text-2xl font-bold">Security</h2></div>
      <div id="preferences" class="tab hidden"><h2 class="text-2xl font-bold">Preferences</h2></div>
      <div id="privacy" class="tab hidden"><h2 class="text-2xl font-bold">Privacy</h2></div>
      <div id="apps" class="tab hidden"><h2 class="text-2xl font-bold">Connected Apps</h2></div>
    </main>

          <!-- Logout Panel -->
      <section id="logout" class="panel hidden">
        <h1 class="text-xl font-bold text-red-600">You have logged out successfully.</h1>
      </section>
  </div>

  <script src="/assets/js/profile.js"></script>

</body>
</html>
