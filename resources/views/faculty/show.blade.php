@extends('layouts.view')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded shadow-md mt-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-semibold text-gray-800">Faculty Details</h2>
        <a href="{{ url('/admin') }}" class="bg-[#D5451B] hover:bg-[#aa3715] text-white px-4 py-2 rounded text-sm">
            ← Back to Admin
        </a>
    </div>

    <!-- Layout: Picture + Info -->
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Profile Picture -->
        <div class="flex-shrink-0 text-center">
            @if ($faculty->profile_picture)
                <img src="{{ asset('storage/' . $faculty->profile_picture) }}"
                     alt="Profile Picture"
                     class="w-40 h-40 rounded-full object-cover border shadow">
            @else
                <div class="w-40 h-40 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                    No Image
                </div>
            @endif
        </div>

        <!-- Info -->
        <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm text-gray-700">
            <div><strong>ID:</strong> {{ $faculty->id }}</div>
            <div><strong>Name:</strong> {{ $faculty->name }}</div>
            <div><strong>Email:</strong> {{ $faculty->email }}</div>
            <div><strong>Role:</strong> {{ ucfirst($faculty->role) }}</div>
            <div><strong>Department:</strong> {{ $faculty->department->name ?? $faculty->department_id }}</div>
            <div>
                <strong>Status:</strong>
                <span class="inline-block px-2 py-1 rounded text-xs {{ $faculty->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ ucfirst($faculty->status ?? 'inactive') }}
                </span>
            </div>
            <div><strong>Email Verified At:</strong> {{ $faculty->email_verified_at ?? 'Not verified' }}</div>
            <div><strong>Remember Token:</strong> {{ $faculty->remember_token ?? '—' }}</div>
            <div><strong>Created At:</strong>
                {{ $faculty->created_at ? $faculty->created_at->format('F j, Y h:i A') : 'N/A' }}
            </div>
            <div><strong>Updated At:</strong>
                {{ $faculty->updated_at ? $faculty->updated_at->format('F j, Y h:i A') : 'N/A' }}
            </div>
        </div>
    </div>

    <!-- Export Buttons -->
    <div class="mt-8 flex flex-wrap gap-4">
        <form method="POST" action="{{ route('faculty.export', $faculty->id) }}">
            @csrf
            <button type="submit" name="format" value="pdf" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm">
                Download PDF
            </button>
            <button type="submit" name="format" value="excel" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">
                Download Excel
            </button>
            <button type="submit" name="format" value="json" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                Download JSON
            </button>
        </form>
    </div>
</div>
@endsection
