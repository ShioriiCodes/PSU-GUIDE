@extends('layouts.view')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow text-black">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Edit Announcement Details</h2>
        @php
            $role = auth()->user()->role;
            $backRoute = match ($role) {
                'admin' => route('dashboard.admin'),
                'registrar' => route('dashboard.registrar'),
                'usg' => route('dashboard.usg'),
                default => url('/'),
            };
        @endphp
        <a href="{{ $backRoute }}" class="bg-[#D5451B] hover:bg-[#aa3715] text-white px-4 py-2 rounded text-sm flex items-center gap-2">

            <!-- Left Arrow Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Admin
        </a>
    </div>

    <form action="{{ route('announcements.update', $announcement->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block mb-1 text-sm font-medium">Title</label>
            <input type="text" name="title" value="{{ $announcement->title }}" required class="w-full p-2 border rounded">
        </div>

        <div>
            <label class="block mb-1 text-sm font-medium">Content</label>
            <textarea name="content" rows="5" required class="w-full p-2 border rounded">{{ $announcement->content }}</textarea>
        </div>

        <div>
            <label class="block mb-1 text-sm font-medium">Category</label>
            <select name="category_id" required class="w-full p-2 border rounded">
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $announcement->category_id == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
    </form>
</div>
@endsection
