{{-- resources/views/partials/edit_comment.blade.php --}}
<div class="p-4 max-w-xl mx-auto bg-white rounded shadow">
    <h2 class="text-lg font-semibold mb-4">Edit Comment</h2>

    <form action="{{ route('comments.update', $comment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <textarea name="content" rows="4" class="w-full border rounded p-2" required>{{ old('content', $comment->content) }}</textarea>

        <div class="flex justify-end gap-2 mt-3">
            <a href="{{ url()->previous() }}" class="text-sm text-gray-600 hover:underline">Cancel</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">
                Update
            </button>
        </div>
    </form>
</div>
