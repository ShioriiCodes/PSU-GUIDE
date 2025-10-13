@php
  $isOwn = auth()->check() && $comment->user_id === auth()->id();
  $isLiked = auth()->check() && $comment->isLikedBy(auth()->user());
  $likeCount = $comment->likes->count();
@endphp

<div id="comment-{{ $comment->id }}" class="bg-gray-50 border border-gray-200 rounded-lg p-4 {{ $level > 0 ? 'ml-6 mt-4' : '' }}" x-data="{ editing: false, replying: false }">
  <div class="flex items-start gap-3">
    <div class="w-8 h-8 bg-[#D5451B] rounded-full flex items-center justify-center text-white text-sm font-bold">
      {{ substr($comment->user->name, 0, 1) }}
    </div>
    <div class="flex-1">
      <div class="flex items-center gap-2 mb-1">
        <p class="text-sm font-semibold text-gray-800">{{ $comment->user->name }}</p>
        <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
        @if($comment->edited_at)
          <span class="text-xs text-gray-400">(edited)</span>
        @endif
      </div>

      {{-- View or Edit --}}
      <div x-show="!editing" class="mb-3">
        <p class="text-sm text-gray-800">{{ $comment->content }}</p>
      </div>

      {{-- Edit Form --}}
      <div x-show="editing" x-cloak class="mb-3">
        <form method="POST" action="{{ route('comments.update', $comment->id) }}" class="space-y-2">
          @csrf
          @method('PUT')
          <textarea name="content" rows="3" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D5451B]" required>{{ old('content', $comment->content) }}</textarea>
          <div class="flex gap-2">
            <button type="submit" class="px-3 py-1 bg-[#D5451B] text-white rounded-lg hover:bg-[#FF9B45] transition">Save</button>
            <button type="button" @click="editing = false" class="px-3 py-1 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">Cancel</button>
          </div>
        </form>
      </div>

      {{-- Actions --}}
      @auth
        <div class="flex items-center gap-4 text-xs mb-3">
          {{-- Like --}}
          <form method="POST" action="{{ route('comments.like', $comment->id) }}" class="inline">
            @csrf
            <button type="submit" class="flex items-center gap-1 {{ $isLiked ? 'text-blue-600' : 'text-gray-600' }} hover:text-blue-600 transition">
              <svg class="w-4 h-4" fill="{{ $isLiked ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
              </svg>
              {{ $likeCount }}
            </button>
          </form>

          {{-- Reply --}}
          <button @click="replying = !replying" class="text-blue-600 hover:text-blue-700 transition">Reply</button>

          {{-- Edit/Delete --}}
          @if($isOwn)
            <button @click="editing = true" class="text-gray-600 hover:text-gray-700 transition">Edit</button>
            <form method="POST" action="{{ route('comments.destroy', $comment->id) }}" onsubmit="return confirm('Delete comment?')" class="inline">
              @csrf
              @method('DELETE')
              <button type="submit" class="text-red-600 hover:text-red-700 transition">Delete</button>
            </form>
          @endif
        </div>
      @endauth

      {{-- Reply Form --}}
      @auth
        <div x-show="replying" x-cloak class="mb-3">
          <form method="POST" action="{{ route('comments.store') }}" class="space-y-2">
            @csrf
            <input type="hidden" name="announcement_id" value="{{ $comment->announcement_id }}">
            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
            <textarea name="content" rows="2" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D5451B]" placeholder="Write a reply..." required></textarea>
            <div class="flex gap-2">
              <button type="submit" class="px-3 py-1 bg-[#D5451B] text-white rounded-lg hover:bg-[#FF9B45] transition">Post Reply</button>
              <button type="button" @click="replying = false" class="px-3 py-1 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">Cancel</button>
            </div>
          </form>
        </div>
      @endauth

      {{-- Nested Replies --}}
      @if($comment->replies->count())
        <div class="mt-4 space-y-4">
          @foreach($comment->replies as $reply)
            @include('partials.comment_item', ['comment' => $reply, 'level' => $level + 1])
          @endforeach
        </div>
      @endif
    </div>
  </div>
</div>
