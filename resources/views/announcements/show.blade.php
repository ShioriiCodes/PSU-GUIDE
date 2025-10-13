@extends('layouts.view')

@section('content')
<div class="max-w-5xl mx-auto bg-white px-6 py-8 sm:px-8 sm:py-10 rounded-xl shadow-md">
    {{-- Top Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <h2 class="text-2xl sm:text-3xl font-semibold text-gray-800">Announcement Details</h2>
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

    {{-- Image and Content --}}
    <div class="flex flex-col lg:flex-row gap-8 items-start">
       {{-- Poster Image --}}
        @if($announcement->poster_image)
            @php
                $ext = strtolower(pathinfo($announcement->poster_image, PATHINFO_EXTENSION));
            @endphp
            <div class="mx-auto lg:mx-0 flex-shrink-0 rounded-lg overflow-hidden border border-gray-300 shadow bg-white"
                style="width: 200px; height: 200px;">
                @if(in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']))
                    <img src="{{ asset('storage/' . $announcement->poster_image) }}"
                        alt="Announcement Poster"
                        class="w-[200px] h-[200px] object-cover" />
                @elseif($ext === 'pdf')
                    <a href="{{ asset('storage/' . $announcement->poster_image) }}" target="_blank" class="flex items-center justify-center h-full">
                        <img src="{{ asset('image/icon/pdf-(1).svg') }}"
                            alt="PDF File"
                            class="w-24 h-24" />
                    </a>
                @else
                    <div class="flex items-center justify-center h-full text-gray-400 text-sm">Unsupported file</div>
                @endif
            </div>
        @endif

        {{-- Title and Content --}}
        <div class="flex-1 text-left space-y-4 mt-4 lg:mt-0 w-full">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 break-words">{{ $announcement->title }}</h1>

            {{-- Metadata --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-700">
                <div>
                    <strong>Posted by:</strong> {{ $announcement->user->name ?? '—' }}
                </div>
                <div>
                    <strong>Role:</strong> {{ $announcement->user->role ?? '—' }}
                </div>
                <div>
                    <strong>Date:</strong> {{ $announcement->created_at->format('F j, Y h:i A') }}
                </div>
                <div>
                    <strong>Status:</strong>
                    <span class="inline-block px-2 py-1 rounded text-xs ml-2
                        {{ $announcement->status === 'approved' ? 'bg-green-100 text-green-700' :
                           ($announcement->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                        {{ ucfirst($announcement->status) }}
                    </span>
                </div>
                <div class="sm:col-span-2">
                    <strong>Category:</strong> {{ $announcement->category->name ?? '—' }}{{ $announcement->custom_category ? ' (' . $announcement->custom_category . ')' : '' }}
                </div>
            </div>

            {{-- Content --}}
            <div class="text-gray-800 leading-relaxed border border-gray-200 rounded-lg p-6 bg-gray-50 whitespace-pre-line break-words w-full">
                {!! nl2br(e($announcement->content)) !!}
            </div>

            {{-- Actions --}}
            <div class="flex flex-wrap gap-4 pt-4">
                @if($announcement->status === 'rejected' && $announcement->user_id === auth()->id())
                    <a href="{{ route('announcements.edit', $announcement->id) }}"
                       class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700 transition">
                        Edit Announcement
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
