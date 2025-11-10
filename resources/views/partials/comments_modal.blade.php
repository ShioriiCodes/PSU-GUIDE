<div class="p-6 max-w-3xl mx-auto bg-white rounded shadow-lg">
    <h2 class="text-xl font-bold mb-4">{{ $announcement->title }}</h2>

    <p class="text-sm text-gray-600 mb-2">
        Posted: {{ $announcement->created_at->format('F j, Y') }} • {{ ucfirst($announcement->user->role ?? 'Unknown') }}
    </p>

    @if($announcement->poster_image)
        @php
            $posterRelative = str_contains($announcement->poster_image, '/')
                ? ltrim($announcement->poster_image, '/')
                : 'posters/' . $announcement->poster_image;
            $ext = strtolower(pathinfo($posterRelative, PATHINFO_EXTENSION));
        @endphp
        @if(in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']))
            <img src="{{ asset('storage/' . $posterRelative) }}" alt="Poster" class="w-full max-h-[400px] object-contain rounded mb-4">
        @elseif($ext === 'pdf')
            <div class="w-full max-h-[400px] flex items-center justify-center bg-gray-100 rounded mb-4">
                <a href="{{ asset('storage/' . $posterRelative) }}" target="_blank" class="flex flex-col items-center">
                    <img src="{{ asset('image/icon/pdf-(1).svg') }}" alt="PDF File" class="w-24 h-24 mb-2">
                    <span class="text-sm text-gray-600">Click to view PDF</span>
                </a>
            </div>
        @else
            <div class="w-full max-h-[400px] flex items-center justify-center bg-gray-100 rounded mb-4 text-gray-400 text-sm">
                Unsupported file type
            </div>
        @endif
    @endif

    <p class="text-gray-800 mb-6 whitespace-pre-line">{!! autoLinkUrls($announcement->content) !!}</p>

    @auth
        <form method="POST" action="{{ route('comments.store') }}" class="mb-6">
            @csrf
            <input type="hidden" name="announcement_id" value="{{ $announcement->id }}">
            <textarea name="content" rows="3" class="w-full p-2 border rounded" placeholder="Write a comment..." required></textarea>
            <button type="submit" class="mt-2 bg-[#D5451B] text-white px-4 py-2 rounded text-sm">Post Comment</button>
        </form>
    @endauth

    @if ($announcement->comments->count())
        <h3 class="font-semibold mb-2 text-gray-800">Comments:</h3>
        <div class="space-y-4">
            @foreach($announcement->comments()->whereNull('parent_id')->latest()->get() as $comment)
                <div id="comment-{{ $comment->id }}" class="bg-gray-50 border p-3 rounded scroll-mt-16">
                    <p class="text-sm font-semibold">{{ $comment->user->name }}</p>

                    {{-- Check if this comment is in edit mode --}}
                    @if(request('edit') == $comment->id && auth()->id() === $comment->user_id)
                        <form method="POST" action="{{ route('comments.update', $comment->id) }}" class="space-y-2 mt-2">
                            @csrf
                            @method('PUT')
                            <textarea name="content" rows="3" class="w-full border rounded p-2 text-sm" required>{{ old('content', $comment->content) }}</textarea>
                            <div class="flex gap-3 text-xs">
                                <a href="{{ url()->current() }}" class="text-gray-600 hover:underline">Cancel</a>
                                <button type="submit" class="text-white bg-blue-600 px-3 py-1 rounded hover:bg-blue-700">Save</button>
                            </div>
                        </form>
                    @else
                        <p class="text-sm text-gray-800 mt-1">{{ $comment->content }}</p>

                        @auth
                            @if ($comment->user_id === auth()->id())
                                <div class="mt-2 flex items-center gap-4 text-xs">
                                    <a href="{{ request()->fullUrlWithQuery(['edit' => $comment->id]) }}#comment-{{ $comment->id }}"
                                    class="text-blue-600 hover:underline">Edit</a>

                                    <form method="POST" action="{{ route('comments.destroy', $comment->id) }}" onsubmit="return confirm('Delete comment?')">
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
    @else
        <p class="text-sm text-gray-500">No comments yet.</p>
    @endif
</div>

<script>
    function setEditComment(id) {
        const url = new URL(window.location.href);
        url.searchParams.set('edit', id);
        fetch(url).then(response => response.text()).then(html => {
            document.getElementById('modalContent').innerHTML = html;
        });
    }

    function cancelEdit(id) {
        const url = new URL(window.location.href);
        url.searchParams.delete('edit');
        fetch(url).then(response => response.text()).then(html => {
            document.getElementById('modalContent').innerHTML = html;
        });
    }
</script>
