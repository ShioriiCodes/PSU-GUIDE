@extends('layouts.custom')

@section('title', 'Password Reset Requests')

@section('content')
  <section class="container mx-auto px-4 sm:px-6 py-12 min-h-screen">
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-800">Password Reset Requests</h1>
      <p class="text-gray-600 mt-2 max-w-2xl">
        Approve a request to trigger Laravel&rsquo;s official password reset email, or decline it if the user should not reset their credentials yet.
      </p>
    </div>

    @if(session('status'))
      <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
        {{ session('status') }}
      </div>
    @endif

    @if($errors->any())
      <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
        <ul class="list-disc list-inside">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="bg-white rounded-2xl shadow overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Handled By</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @forelse($requests as $request)
            <tr>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-semibold text-gray-900">{{ $request->email }}</div>
                <div class="text-xs text-gray-500">{{ optional($request->user)->name ?? 'Account not found' }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                @php
                  $statusColors = [
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'approved' => 'bg-green-100 text-green-800',
                    'declined' => 'bg-red-100 text-red-800',
                  ];
                @endphp
                <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$request->status] ?? 'bg-gray-100 text-gray-700' }}">
                  {{ ucfirst($request->status) }}
                </span>
                @if($request->status === 'approved' && $request->approved_at)
                  <div class="text-xs text-gray-500 mt-1">{{ $request->approved_at->diffForHumans() }}</div>
                @elseif($request->status === 'declined' && $request->declined_at)
                  <div class="text-xs text-gray-500 mt-1">{{ $request->declined_at->diffForHumans() }}</div>
                @endif
                @if($request->decline_reason)
                  <div class="text-xs text-red-500 mt-1">Reason: {{ $request->decline_reason }}</div>
                @endif
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ optional($request->requested_at)->timezone(config('app.timezone'))->toDayDateTimeString() }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ optional($request->handler)->name ?? '—' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <div class="flex flex-col gap-2">
                  <form method="POST" action="{{ route('admin.password-resets.approve', $request) }}">
                    @csrf
                    <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg disabled:opacity-50" {{ $request->status !== 'pending' ? 'disabled' : '' }}>
                      Approve &amp; Email Link
                    </button>
                  </form>
                  <form method="POST" action="{{ route('admin.password-resets.decline', $request) }}" class="flex flex-col gap-2">
                    @csrf
                    <input type="text" name="reason" placeholder="Reason (optional)"
                           class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-400 focus:outline-none text-sm">
                    <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg disabled:opacity-50" {{ $request->status !== 'pending' ? 'disabled' : '' }}>
                      Decline Request
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-6 py-8 text-center text-gray-500">No password reset requests found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-6">
      {{ $requests->links() }}
    </div>
  </section>
@endsection

