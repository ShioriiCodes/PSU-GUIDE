<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-600">
        {{ __('Forgot your password? No problem. Just enter your email address below and we’ll send you a password reset link.') }}
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 text-sm font-medium text-green-600 dark:text-green-400">
            {{ session('status') }}
        </div>
    @endif

    <!-- Form -->
    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ __('Email Address') }}
            </label>
            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email') }}"
                required
                autofocus
                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-orange-500 focus:ring focus:ring-orange-200 dark:focus:border-orange-400 dark:focus:ring-orange-600 sm:text-sm"
            />
            @error('email')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="flex justify-center w-full">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-[#D5451B] border border-transparent rounded-md font-semibold text-white hover:bg-[#aa3715] transition">
                {{ __('Email Password Reset Link') }}
            </button>
        </div>
    </form>
</x-guest-layout>
