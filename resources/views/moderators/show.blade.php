@extends('layouts.view')

@section('content')
    <div class="max-w-5xl mx-auto bg-white px-6 py-8 sm:px-10 sm:py-10 rounded-2xl shadow-md mt-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Moderator Details</h2>
                <p class="text-sm text-gray-500 mt-1">ID #{{ $moderator->id }}</p>
            </div>
            <a href="{{ url('/admin') }}" class="inline-flex items-center gap-2 bg-[#D5451B] hover:bg-[#aa3715] text-white px-4 py-2 rounded-lg text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Admin
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[auto,1fr] gap-8 items-start">
            <!-- Avatar Card -->
            <div class="w-full lg:w-[280px] bg-gray-50 rounded-2xl border border-gray-200 p-6 text-center">
                @if ($moderator->profile_picture)
                    @php
                        $moderatorProfilePath = str_contains($moderator->profile_picture, '/')
                            ? ltrim($moderator->profile_picture, '/')
                            : 'profile_pictures/' . $moderator->profile_picture;
                    @endphp
                    <img src="{{ asset('storage/' . $moderatorProfilePath) }}" alt="Profile Picture"
                         class="w-40 h-40 rounded-full object-cover mx-auto ring-2 ring-offset-2 ring-[#D5451B]">
                @else
                    <div class="w-40 h-40 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 mx-auto">
                        No Image
                    </div>
                @endif
                <div class="mt-4">
                    <span class="inline-flex items-center px-2 py-1 text-xs rounded-full bg-slate-100 text-slate-700 border">
                        {{ ucfirst($moderator->role) }}
                    </span>
                </div>
            </div>

            <!-- Details Card -->
            <div class="space-y-6">
                <div class="rounded-2xl border border-gray-200 p-6">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-700">
                        <div><dt class="text-gray-500">Name</dt><dd class="font-medium text-gray-900">{{ $moderator->name }}</dd></div>
                        <div><dt class="text-gray-500">Email</dt><dd class="font-medium text-gray-900 break-all">{{ $moderator->email }}</dd></div>
                        <div><dt class="text-gray-500">Department</dt><dd class="font-medium text-gray-900">{{ $moderator->department->name ?? $moderator->department_id }}</dd></div>
                        <div>
                            <dt class="text-gray-500">Status</dt>
                            <dd>
                                <span class="inline-block px-2 py-1 rounded text-xs {{ $moderator->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ ucfirst($moderator->status) }}
                                </span>
                            </dd>
                        </div>
                        <div><dt class="text-gray-500">Email Verified</dt><dd class="font-medium">{{ $moderator->email_verified_at ?? 'Not verified' }}</dd></div>
                        <div><dt class="text-gray-500">Remember Token</dt><dd class="font-medium truncate">{{ $moderator->remember_token ?? '—' }}</dd></div>
                        <div><dt class="text-gray-500">Created</dt><dd class="font-medium">{{ $moderator->created_at->format('F j, Y h:i A') }}</dd></div>
                        <div><dt class="text-gray-500">Updated</dt><dd class="font-medium">{{ $moderator->updated_at->format('F j, Y h:i A') }}</dd></div>
                    </dl>
                </div>

                <!-- Export Actions -->
                <div class="rounded-2xl border border-gray-200 p-6">
                    <form method="POST" action="{{ route('moderators.export', $moderator->id) }}" class="flex flex-wrap gap-3">
                        @csrf
                        <button type="submit" name="format" value="pdf" class="px-4 py-2 rounded-lg text-sm bg-red-600 hover:bg-red-700 text-white">Download PDF</button>
                        {{-- <button type="submit" name="format" value="excel" class="px-4 py-2 rounded-lg text-sm bg-green-600 hover:bg-green-700 text-white">Download Excel</button>
                        <button type="submit" name="format" value="txt" class="px-4 py-2 rounded-lg text-sm bg-blue-600 hover:bg-blue-700 text-white">Download TXT</button> --}}
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
