<div class="p-6 max-w-3xl mx-auto bg-white rounded shadow-lg">
    <h2 class="text-xl font-bold mb-4">{{ $announcement->title }}</h2>
    <p class="text-sm text-gray-600 mb-2">
        Posted: {{ $announcement->created_at->format('F j, Y') }} • {{ ucfirst($announcement->user->role ?? 'Unknown') }}
    </p>

    @if($announcement->poster_image)
        <img src="{{ asset('storage/' . $announcement->poster_image) }}" alt="Poster" class="w-full max-h-[400px] object-contain rounded mb-4">
    @endif

    <p class="text-gray-800 mb-6 whitespace-pre-line">{{ $announcement->content }}</p>

    {{-- Comment Form --}}
    @auth
        <form method="POST" action="{{ route('comments.store') }}" class="mb-6">
            @csrf
            <input type="hidden" name="announcement_id" value="{{ $announcement->id }}">
            <textarea name="content" rows="3" class="w-full p-2 border rounded" placeholder="Write a comment..." required></textarea>
            <button class="mt-2 bg-[#D5451B] text-white px-4 py-2 rounded text-sm">Post Comment</button>
        </form>
    @endauth

    {{-- Comments --}}
    @if ($announcement->comments->count())
        <h3 class="font-semibold mb-2 text-gray-800">Comments:</h3>
        <div class="space-y-4">
            @foreach($announcement->comments()->whereNull('parent_id')->latest()->get() as $comment)
            
            <div id="comment-{{ $comment->id }}" class="bg-gray-50 border p-3 rounded scroll-mt-16" x-data="{ editing: false }">
                <p class="text-sm font-semibold">{{ $comment->user->name }}</p>

                {{-- View or Edit --}}
                <div x-show="!editing">
                    <p class="text-sm text-gray-800 mt-1">{{ $comment->content }}</p>
                    @auth
                        @if ($comment->user_id === auth()->id())
                            <div class="mt-2 flex items-center gap-4 text-xs">
                                <button @click="editing = true" class="text-blue-600 hover:underline">Edit</button>
                                <form method="POST" action="{{ route('comments.destroy', $comment->id) }}" onsubmit="return confirm('Delete comment?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>
                
                {{-- Replies --}}
                @if ($comment->replies->count())
                    <div class="mt-3 space-y-3 ml-4 border-l-2 border-gray-200 pl-3">
                        @foreach ($comment->replies as $reply)
                            <div id="comment-{{ $reply->id }}" class="text-sm text-gray-800">

                                {{-- Editing Reply --}}
                                @if(request('edit') == $reply->id && auth()->id() === $reply->user_id)
                                    <form method="POST" action="{{ route('comments.update', $reply->id) }}" class="space-y-2">
                                        @csrf
                                        @method('PUT')
                                        <textarea name="content" rows="2" class="w-full border rounded p-1 text-sm" required>{{ old('content', $reply->content) }}</textarea>
                                        <div class="flex gap-2 text-xs mt-1">
                                            <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
                                            <a href="#" onclick="cancelEdit({{ $reply->id }})" class="text-gray-500 hover:underline">Cancel</a>
                                        </div>
                                    </form>
                                @else
                                    {{-- Normal Reply Display --}}
                                    <p>
                                        <span class="font-semibold">{{ $reply->user->name }}:</span>
                                        {{ $reply->content }}
                                    </p>

                                    @auth
                                        @if ($reply->user_id === auth()->id())
                                            <div class="mt-1 flex items-center gap-3 text-xs">
                                                <button onclick="setEditComment({{ $reply->id }})" class="text-blue-600 hover:underline">Edit</button>
                                                <form method="POST" action="{{ route('comments.destroy', $reply->id) }}" onsubmit="return confirm('Delete reply?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                                </form>
                                            </div>
                                        @endif
                                    @endauth
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Inline Edit Form --}}
                <div x-show="editing" x-cloak>
                    <form method="POST" action="{{ route('comments.update', $comment->id) }}" class="space-y-2 mt-2">
                        @csrf @method('PUT')
                        <textarea name="content" rows="3" class="w-full border rounded p-2 text-sm" required>{{ old('content', $comment->content) }}</textarea>
                        <div class="flex items-center gap-3 text-xs">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">Save</button>
                            <button type="button" @click="editing = false" class="text-gray-600 hover:underline">Cancel</button>
                        </div>
                    </form>
                </div>

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
            </div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-gray-500">No comments yet.</p>
    @endif
</div>
