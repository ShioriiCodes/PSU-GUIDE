@extends('layouts.view')

@section('content')
<div class="max-w-4xl mx-auto bg-white px-6 py-8 sm:px-8 sm:py-10 rounded-xl shadow-md mt-6">
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
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Admin
        </a>
    </div>

    {{-- Image and Content --}}
    <div class="flex flex-col sm:flex-row gap-8 items-start">
       {{-- Poster Image --}}
        @if($announcement->poster_image)
            <div class="mx-auto sm:mx-0 flex-shrink-0 rounded-lg overflow-hidden border border-gray-300 shadow bg-white"
                style="width: 150px; height: 150px;">
                <img src="{{ asset('storage/' . $announcement->poster_image) }}"
                    alt="Announcement Poster"
                    class="w-[150px] h-[150px] object-cover" />
            </div>
        @endif


        {{-- Title and Content --}}
        <div class="flex-1 text-left space-y-4 mt-4 sm:mt-0 w-full break-words">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 break-words">{{ $announcement->title }}</h1>

            <div class="text-sm text-gray-700 break-words">
                <strong>Posted by:</strong> {{ $announcement->user->name ?? '—' }}
            </div>

            <div class="text-sm text-gray-700 break-words">
                <strong>Role:</strong> {{ $announcement->user->role ?? '—' }}
            </div>

            <div class="text-sm text-gray-700 break-words">
                <strong>Date:</strong> {{ $announcement->created_at->format('F j, Y h:i A') }}
            </div>

            <div class="text-sm text-gray-700 break-words">
                <strong>Status:</strong>
                <span class="inline-block px-2 py-1 rounded text-xs
                    {{ $announcement->status === 'approved' ? 'bg-green-100 text-green-700' :
                       ($announcement->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                    {{ ucfirst($announcement->status) }}
                </span>
            </div>

            <div class="text-gray-800 leading-relaxed border border-gray-200 rounded-lg p-5 bg-gray-50 whitespace-pre-line break-words w-full">
                {!! nl2br(e($announcement->content)) !!}
            </div>
        </div>
    </div>
</div>
@endsection
