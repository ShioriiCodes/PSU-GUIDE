    <h2 class="text-xl font-bold mb-4">{{ $announcement->title }}</h2>
        <p class="text-sm text-gray-600 mb-2">
        Posted: {{ $announcement->created_at->format('F j, Y') }} • {{ ucfirst($announcement->user->role) ?? 'Unknown' }}
        </p>
    <p class="text-gray-800 mb-4">{{ $announcement->content }}</p>

    {{-- Comment Form --}}
    @auth
        <form method="POST" action="{{ route('comments.store') }}" class="mb-6">
            @csrf
            <input type="hidden" name="announcement_id" value="{{ $announcement->id }}">
            <textarea name="content" rows="3" class="w-full p-2 border rounded" placeholder="Write a comment..." required></textarea>
            <button class="mt-2 bg-[#D5451B] text-white px-4 py-2 rounded text-sm">Post Comment</button>
        </form>
    @endauth

    {{-- Comments List --}}
    @if ($announcement->comments->count())
        <h3 class="font-semibold mb-2 text-gray-800">Comments:</h3>
        <div class="space-y-4">
            @foreach($announcement->comments()->whereNull('parent_id')->latest()->get() as $comment)
            <div class="bg-gray-50 border p-3 rounded">
                <p class="text-sm font-semibold">{{ $comment->user->name }}</p>
                <p class="text-sm text-gray-800">{{ $comment->content }}</p>

                {{-- Delete comment --}}
                @auth
                @if ($comment->user_id === auth()->id())
                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Delete comment?')" class="mt-1">
                    @csrf @method('DELETE')
                    <button class="text-xs text-red-600">Delete</button>
                    </form>
                @endif
                @endauth

                {{-- Reply Form --}}
                @auth
                <form method="POST" action="{{ route('comments.store') }}" class="mt-2 ml-4">
                    @csrf
                    <input type="hidden" name="announcement_id" value="{{ $announcement->id }}">
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                    <input type="text" name="content" class="w-full p-1 border rounded text-sm" placeholder="Write a reply..." required>
                    <button class="text-xs text-blue-600 mt-1">Reply</button>
                </form>
                @endauth

                {{-- Replies --}}
                @foreach ($comment->replies as $reply)
                <div class="ml-4 mt-3 border-l-2 border-gray-200 pl-3">
                    <p class="text-xs font-semibold">{{ $reply->user->name }}</p>
                    <p class="text-sm">{{ $reply->content }}</p>

                    @auth
                    @if ($reply->user_id === auth()->id())
                        <form method="POST" action="{{ route('comments.destroy', $reply->id) }}" onsubmit="return confirm('Delete reply?')" class="mt-1">
                        @csrf @method('DELETE')
                        <button class="text-xs text-red-600">Delete</button>
                        </form>
                    @endif
                    @endauth
                </div>
                @endforeach
            </div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-gray-500">No comments yet.</p>
    @endif
