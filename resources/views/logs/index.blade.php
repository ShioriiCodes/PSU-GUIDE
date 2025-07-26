@extends('layouts.view')

@section('content')

{{-- Role Filter Dropdown --}}
    <div class="mb-6 flex gap-4">
        <a href="{{ route('logs.export', ['type' => 'csv']) }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Export CSV</a>
        <a href="{{ route('logs.export', ['type' => 'pdf']) }}" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Export PDF</a>
    </div>

    <form method="GET" class="mb-4">
        <select name="role" onchange="this.form.submit()" class="border p-2 rounded">
            <option value="">All Roles</option>
            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="registrar" {{ request('role') === 'registrar' ? 'selected' : '' }}>Registrar</option>
            <option value="usg" {{ request('role') === 'usg' ? 'selected' : '' }}>USG</option>
        </select>
    </form>

    <table class="table-auto w-full text-sm border">
        <thead>
            <tr class="bg-gray-100">
            <th class="px-4 py-2">User</th>
            <th class="px-4 py-2">Role</th>
            <th class="px-4 py-2">Action</th>
            <th class="px-4 py-2">Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
            <tr>
                <td class="px-4 py-2">{{ $log->user->name ?? 'N/A' }}</td>
                <td class="px-4 py-2">{{ $log->user->role ?? 'N/A' }}</td>
                <td class="px-4 py-2">{{ $log->action }}</td>
                <td class="px-4 py-2">{{ $log->timestamp->format('Y-m-d H:i') }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center py-4">No logs found.</td></tr>
            @endforelse
        </tbody>
    </table>

@endsection
