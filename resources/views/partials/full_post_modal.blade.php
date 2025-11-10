<div class="p-6 max-w-3xl mx-auto bg-white rounded shadow-lg">
    <h2 class="text-xl font-bold mb-4">{{ $announcement->title }}</h2>
    <p class="text-sm text-gray-600 mb-2">
        Posted: {{ $announcement->created_at->format('F j, Y') }} • {{ ucfirst($announcement->user->role ?? 'Unknown') }}
    </p>

    @php
        $galleryImages = [];
        $pdfAttachment = null;

        if ($announcement->poster_image) {
            $posterRelative = str_contains($announcement->poster_image, '/')
                ? ltrim($announcement->poster_image, '/')
                : 'posters/' . $announcement->poster_image;
            $posterExt = strtolower(pathinfo($posterRelative, PATHINFO_EXTENSION));
            if (in_array($posterExt, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'])) {
                $galleryImages[] = $posterRelative;
            } elseif ($posterExt === 'pdf') {
                $pdfAttachment = $posterRelative;
            }
        }

        if ($announcement->images && $announcement->images->count()) {
            foreach ($announcement->images as $image) {
                $galleryImages[] = str_contains($image->path, '/')
                    ? ltrim($image->path, '/')
                    : 'posters/' . $image->path;
            }
        }
    @endphp

    @if(count($galleryImages))
        @php
            $firstImgPath = $galleryImages[0];
            $imgCount = count($galleryImages);
        @endphp
        <div class="mb-4">
            <div class="relative">
                <img src="{{ asset('storage/' . $firstImgPath) }}" alt="Announcement Image"
                     class="w-full max-h-[400px] object-cover rounded cursor-pointer"
                     data-lightbox-image
                     onclick="openImageLightboxFromElement(this)">
                @if($imgCount > 1)
                    <div class="absolute inset-0 bg-black/30 rounded pointer-events-none"></div>
                    <div class="absolute bottom-3 right-3 bg-black/70 text-white text-sm font-semibold px-3 py-1 rounded-full select-none">
                        +{{ $imgCount - 1 }}
                    </div>
                @endif
            </div>
            {{-- Hidden images to complete the gallery for the lightbox --}}
            @foreach(array_slice($galleryImages, 1) as $imgPath)
                <img src="{{ asset('storage/' . $imgPath) }}" alt="Announcement Image"
                     class="hidden"
                     data-lightbox-image
                     onclick="openImageLightboxFromElement(this)">
            @endforeach
        </div>
    @endif

    @if($pdfAttachment)
        <div class="w-full max-h-[400px] flex items-center justify-center bg-gray-100 rounded mb-4">
            <a href="{{ asset('storage/' . $pdfAttachment) }}" target="_blank" class="flex flex-col items-center">
                <img src="{{ asset('image/icon/pdf-(1).svg') }}" alt="PDF File" class="w-[350px] h-[350px] mb-2">
                <span class="text-sm text-gray-600">Click to view PDF</span>
            </a>
        </div>
    @endif

    <p class="text-gray-800 mb-6 whitespace-pre-line">{!! autoLinkUrls($announcement->content) !!}</p>

    {{-- Comment Form --}}
    @auth
        <form method="POST" action="{{ route('comments.store') }}" class="mb-6" data-ajax-comment>
            @csrf
            <input type="hidden" name="announcement_id" value="{{ $announcement->id }}">
            <textarea name="content" rows="3" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D5451B]" placeholder="Write a comment..." required></textarea>
            <button class="mt-2 bg-gradient-to-r from-[#D5451B] to-[#FF9B45] text-white px-4 py-2 rounded-lg hover:from-[#FF9B45] hover:to-[#D5451B] transition">Post Comment</button>
        </form>
    @endauth

    {{-- Comments --}}
    @if ($announcement->comments->count())
        <h3 id="comments-start" class="font-semibold mb-4 text-gray-800">Comments ({{ $announcement->comments->count() }})</h3>
        <div class="space-y-6">
            @foreach($announcement->comments()->whereNull('parent_id')->latest()->get() as $comment)
                @include('partials.comment_item', ['comment' => $comment, 'level' => 0])
            @endforeach
        </div>
    @else
        <p class="text-sm text-gray-500">No comments yet.</p>
    @endif
</div>

<!-- Image Lightbox Overlay -->
<div id="imageLightboxOverlay" class="fixed inset-0 bg-black bg-opacity-80 z-[100] hidden items-center justify-center p-4" tabindex="0" aria-modal="true" role="dialog">
    <button onclick="closeImageLightbox()" class="absolute top-4 right-6 text-white text-3xl leading-none" aria-label="Close">&times;</button>
    <button onclick="prevImage()" class="absolute left-4 top-1/2 -translate-y-1/2 text-white text-3xl" aria-label="Previous">&#10094;</button>
    <img id="imageLightboxImg" src="" alt="Preview" class="max-w-[90vw] max-h-[85vh] object-contain rounded shadow-lg">
    <button onclick="nextImage()" class="absolute right-4 top-1/2 -translate-y-1/2 text-white text-3xl" aria-label="Next">&#10095;</button>
</div>
