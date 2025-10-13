@extends('layouts.view')

@section('content')
<div class="max-w-4xl mx-auto bg-white px-6 py-8 sm:px-8 sm:py-10 rounded-xl shadow-md">
    {{-- Top Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <h2 class="text-2xl sm:text-3xl font-semibold text-gray-800">Edit Announcement</h2>
        @php
            $role = auth()->user()->role;
            $backRoute = match ($role) {
                'admin' => route('dashboard.admin'),
                'registrar' => route('dashboard.registrar'),
                'usg' => route('dashboard.usg'),
                default => url('/'),
            };
        @endphp
        <a href="{{ $backRoute }}"
           class="bg-[#D5451B] hover:bg-[#aa3715] text-white px-4 py-2 rounded text-sm flex items-center gap-2 whitespace-nowrap">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Dashboard
        </a>
    </div>

    <form action="{{ route('announcements.update', $announcement->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Rejection Reason Display --}}
        @if($rejectionReason)
            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <strong class="font-bold block mb-2">Rejection Reason:</strong>
                <p class="mt-1">{{ $rejectionReason }}</p>
                <p class="text-sm mt-2 text-red-600">Please address the issues mentioned above and resubmit for approval.</p>
            </div>
        @endif

        {{-- Title --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
            <input type="text" name="title" value="{{ $announcement->title }}" required
                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#D5451B]">
        </div>

        {{-- Content --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Content</label>
            <textarea name="content" rows="6" required
                      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#D5451B]">{{ $announcement->content }}</textarea>
        </div>

        {{-- Category --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
            <select name="category_id" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#D5451B]">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $announcement->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Custom Category --}}
        @if($announcement->category->name === 'Others')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Custom Category</label>
                <input type="text" name="custom_category" value="{{ $announcement->custom_category }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#D5451B]">
            </div>
        @endif

        {{-- Poster Image or PDF --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Poster Image or PDF (Optional)</label>
            <input name="poster_image" type="file" accept="image/*,.pdf"
                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#D5451B]">
            <p class="text-xs text-gray-500 mt-1">Accepted formats: Images (JPG, PNG, etc.) and PDF files. Leave empty to keep existing file.</p>
        </div>

        {{-- Submit Button --}}
        <div class="flex justify-end">
            <button type="submit"
                    class="px-6 py-2 bg-[#D5451B] text-white rounded-md hover:bg-[#aa3715] transition">
                Update Announcement
            </button>
        </div>
    </form>
</div>
@endsection
