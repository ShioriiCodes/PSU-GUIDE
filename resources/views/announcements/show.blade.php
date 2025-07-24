@extends('layouts.view')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded shadow-md mt-8">
    {{-- Top Header --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-semibold text-gray-800">Announcement Details</h2>
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
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Admin
        </a>
    </div>

    {{-- Info Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm text-gray-700">
        <div><strong>Posted By:</strong> {{ $announcement->user->name ?? '—' }}</div>
        <div>
            <strong>Status:</strong>
            <span class="inline-block px-2 py-1 rounded text-xs 
                {{ $announcement->status === 'approved' ? 'bg-green-100 text-green-700' : 
                    ($announcement->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                {{ ucfirst($announcement->status) }}
            </span>
        </div>
        <div><strong>Title:</strong> {{ $announcement->title }}</div>
        <div><strong>Category:</strong> {{ $announcement->category->name ?? '—' }}</div>
        <div><strong>Posted By:</strong> {{ $announcement->user->name ?? '—' }} ({{ $announcement->user->role ?? '—' }})</div>
        <div><strong>Email:</strong> {{ $announcement->user->email ?? '—' }}</div>
        <div><strong>Created At:</strong> {{ $announcement->created_at->format('F j, Y h:i A') }}</div>
        <div><strong>Updated At:</strong> {{ $announcement->updated_at->format('F j, Y h:i A') }}</div>
    </div>

    {{-- Poster and Content Layout --}}
    <div class="mt-8 flex flex-col sm:flex-row gap-6 items-start">
        @if($announcement->poster_image)
            <div class="w-full sm:w-1/3">
                <img src="{{ asset('storage/' . $announcement->poster_image) }}"
                    alt="Announcement Poster"
                    class="w-full h-auto max-h-60 object-cover rounded border border-gray-300 shadow">
            </div>
        @endif

        <div class="flex-1">
            <h3 class="text-xl font-semibold mb-2 text-gray-800">Content</h3>
            <div class="text-gray-800 leading-relaxed border rounded p-4 bg-gray-50">
                {!! nl2br(e($announcement->content)) !!}
            </div>
        </div>
    </div>
</div>
@endsection
