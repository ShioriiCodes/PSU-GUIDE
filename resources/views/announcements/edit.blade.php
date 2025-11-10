@extends('layouts.view')

@section('content')
<div class="max-w-4xl mx-auto bg-white px-5 py-6 sm:px-6 sm:py-8 rounded-2xl shadow-md">
    {{-- Top Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h2 class="text-2xl sm:text-3xl font-semibold text-gray-900">Edit Announcement</h2>
            <p class="text-sm text-gray-500 mt-1">Update the details below, then resubmit for approval.</p>
        </div>
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
           class="bg-[#D5451B] hover:bg-[#aa3715] text-white px-4 py-2 rounded-lg text-sm flex items-center gap-2 whitespace-nowrap">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Dashboard
        </a>
    </div>

    {{-- If rejected, show rejection banner --}}
    @if($rejectionReason)
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
            <div class="flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12A9 9 0 113 12a9 9 0 0118 0z" />
                </svg>
                <div>
                    <strong class="block mb-1">Rejection Reason</strong>
                    <p class="leading-relaxed">{{ $rejectionReason }}</p>
                    <p class="text-sm mt-2 text-red-600">Please address the issues and resubmit. The post will go back to Admin pending.</p>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('announcements.update', $announcement->id) }}" method="POST" class="space-y-6" enctype="multipart/form-data" onsubmit="return confirm('Resubmit this announcement for admin approval?')">
        @csrf
        @method('PUT')

        {{-- Display Validation Errors --}}
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <strong class="font-bold block mb-2">Please fix the following errors:</strong>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Inputs --}}
            <div class="lg:col-span-2 space-y-5">
                {{-- Title --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                    <input type="text" name="title" value="{{ $announcement->title }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#D5451B]">
                </div>

                {{-- Content --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-medium text-gray-700">Content</label>
                        <span class="text-xs text-gray-400">Use Shift+Enter for line breaks</span>
                    </div>
                    <textarea name="content" rows="8" required
                              class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#D5451B]">{{ $announcement->content }}</textarea>
                </div>

                {{-- Category --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select name="category_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#D5451B]">
                            @php
                                $userRole = auth()->user()->role;
                                $allowedCategories = match($userRole) {
                                    'usg' => ['USG Announcements', 'Campus Spotlights', 'Workshops & Seminars'],
                                    'registrar' => ['Registrar Notices', 'Enrollment Updates', 'Memorandum', 'Others'],
                                    default => null, // null means no filtering
                                };

                                $filteredCategories = is_array($allowedCategories)
                                    ? $categories->whereIn('name', $allowedCategories)
                                    : $categories;
                            @endphp

                            @foreach($filteredCategories as $category)
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
                </div>
            </div>

            {{-- Sidebar Card --}}
            <div class="space-y-4">
                <div class="border border-gray-200 rounded-xl p-4 bg-gray-50">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Poster</h3>
                    @if($announcement->poster_image)
                        @php
                            $posterRelative = str_contains($announcement->poster_image, '/')
                                ? ltrim($announcement->poster_image, '/')
                                : 'posters/' . $announcement->poster_image;
                            $ext = strtolower(pathinfo($posterRelative, PATHINFO_EXTENSION));
                        @endphp
                        <div class="rounded-lg overflow-hidden border border-gray-200 bg-white mb-2">
                            @if(in_array($ext, ['jpg','jpeg','png','gif','bmp','webp']))
                                <img src="{{ asset('storage/' . $posterRelative) }}" alt="Current Poster" class="w-full h-40 object-cover">
                            @elseif($ext === 'pdf')
                                <a href="{{ asset('storage/' . $posterRelative) }}" target="_blank" class="flex items-center justify-center h-40">
                                    <img src="{{ asset('image/icon/pdf-(1).svg') }}" alt="PDF File" class="w-16 h-16">
                                </a>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 mb-2">Uploading a new file will replace the current poster.</p>
                    @endif
                    <input name="poster_image" type="file" accept="image/*,.pdf"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#D5451B]">
                    <p class="text-xs text-gray-500 mt-2">Accepted: JPG, PNG, GIF, WEBP, or PDF. Max 5MB.</p>
                </div>

                <div class="border border-gray-200 rounded-xl p-4 bg-gray-50">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Tips</h3>
                    <ul class="list-disc pl-5 text-xs text-gray-600 space-y-1">
                        <li>Keep the title short and descriptive.</li>
                        <li>Use clear paragraphs for readability.</li>
                        <li>Ensure the poster is high quality.</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Submit Bar --}}
        <div class="flex items-center justify-end gap-3 pt-1">
            <a href="{{ $backRoute }}" class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-50">Cancel</a>
            <button type="submit"
                    class="px-5 py-2 bg-[#D5451B] text-white rounded-md hover:bg-[#aa3715] transition">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
