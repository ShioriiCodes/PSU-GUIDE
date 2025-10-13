<div class="p-6 max-w-3xl mx-auto bg-white rounded shadow-lg">
    <h2 class="text-xl font-bold mb-4">{{ $announcement->title }}</h2>
    <p class="text-sm text-gray-600 mb-2">
        Posted: {{ $announcement->created_at->format('F j, Y') }} • {{ ucfirst($announcement->user->role ?? 'Unknown') }}
    </p>

    @if($announcement->poster_image)
        @php
            $ext = strtolower(pathinfo($announcement->poster_image, PATHINFO_EXTENSION));
        @endphp
        @if(in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']))
            <img src="{{ asset('storage/' . $announcement->poster_image) }}" alt="Poster" class="w-full max-h-[400px] object-contain rounded mb-4">
        @elseif($ext === 'pdf')
            <div class="w-full max-h-[400px] flex items-center justify-center bg-gray-100 rounded mb-4">
                <a href="{{ asset('storage/' . $announcement->poster_image) }}" target="_blank" class="flex flex-col items-center">
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

    <p class="text-gray-800 mb-6 whitespace-pre-line">{{ $announcement->content }}</p>

    {{-- Comment Form --}}
    @auth
        <form method="POST" action="{{ route('comments.store') }}" class="mb-6">
            @csrf
            <input type="hidden" name="announcement_id" value="{{ $announcement->id }}">
            <textarea name="content" rows="3" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D5451B]" placeholder="Write a comment..." required></textarea>
            <button class="mt-2 bg-gradient-to-r from-[#D5451B] to-[#FF9B45] text-white px-4 py-2 rounded-lg hover:from-[#FF9B45] hover:to-[#D5451B] transition">Post Comment</button>
        </form>
    @endauth

    {{-- Comments --}}
    @if ($announcement->comments->count())
        <h3 class="font-semibold mb-4 text-gray-800">Comments ({{ $announcement->comments->count() }})</h3>
        <div class="space-y-6">
            @foreach($announcement->comments()->whereNull('parent_id')->latest()->get() as $comment)
                @include('partials.comment_item', ['comment' => $comment, 'level' => 0])
            @endforeach
        </div>
    @else
        <p class="text-sm text-gray-500">No comments yet.</p>
    @endif
</div>
