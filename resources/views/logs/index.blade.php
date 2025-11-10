@extends('layouts.view')

@section('content')

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-gray-900">

    {{-- Export & Filter Panel --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-2">
        <h1 class="text-3xl font-bold mb-3 text-[#D5451B] text-center sm:text-left">Activity Logs</h1>
        {{-- Export Buttons --}}
        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <a href="{{ route('activityLogs.export', ['format' => 'pdf']) }}"
               class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 text-center transition">
                Export to PDF
            </a>
            <a href="{{ route('logs.export', ['format' => 'txt']) }}"
               class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 text-center transition">
                Export to TXT
            </a>
        </div>

        {{-- Role Filter Dropdown --}}
        <form method="GET" action="{{ route('logs.index') }}" class="w-full sm:w-auto">
            <select name="role" onchange="this.form.submit()"
                    class="w-full sm:w-auto border-gray-300 border p-2 pr-7 px-4 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                <option value="">All Roles</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="registrar" {{ request('role') === 'registrar' ? 'selected' : '' }}>Registrar</option>
                <option value="usg" {{ request('role') === 'usg' ? 'selected' : '' }}>USG</option>
            </select>
        </form>
    </div>

    {{-- Info Label --}}
    @if($logs->count())
        <p class="text-sm text-gray-600 mb-3">
            Showing {{ $logs->count() }} logs{{ request('role') ? ' for ' . ucfirst(request('role')) : '' }}.
        </p>
    @endif

    {{-- Desktop Table: visible only on sm and up --}}
    <div class="hidden sm:block bg-white rounded-lg shadow border border-gray-200 overflow-y-auto max-h-[500px]">
        <table class="min-w-full table-auto divide-y divide-gray-200 text-sm text-gray-800">
            <thead class="bg-[#f3f4f6] text-gray-700 uppercase text-xs sticky top-0 z-10">
                <tr>
                    <th class="px-6 py-3 text-left">User</th>
                    <th class="px-6 py-3 text-left">Role</th>
                    <th class="px-6 py-3 text-left">Action</th>
                    <th class="px-6 py-3 text-left">Date</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse ($logs as $log)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3">{{ $log->user->name ?? 'N/A' }}</td>
                        <td class="px-6 py-3 capitalize">{{ $log->user->role ?? 'N/A' }}</td>
                        <td class="px-6 py-3">
                            <span class="inline-flex items-center gap-1 text-blue-600 font-medium">
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-gray-600">
                            {{ \Carbon\Carbon::parse($log->timestamp)->format('Y-m-d H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No logs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Cards (visible on screens smaller than sm) --}}
    <div class="sm:hidden space-y-4">
        @forelse($logs as $log)
            <div class="bg-white border border-gray-200 rounded-lg shadow p-4">
                <div class="text-sm"><strong>User:</strong> {{ $log->user->name ?? 'N/A' }}</div>
                <div class="text-sm"><strong>Role:</strong> {{ ucfirst($log->user->role ?? 'N/A') }}</div>
                <div class="text-sm"><strong>Action:</strong> {{ ucfirst($log->action) }}</div>
                <div class="text-sm text-gray-600">
                    <strong>Date:</strong> {{ \Carbon\Carbon::parse($log->timestamp)->format('Y-m-d H:i') }}
                </div>
            </div>
        @empty
            <p class="text-gray-500 text-center">No logs found.</p>
        @endforelse
    </div>

</div>
@endsection
