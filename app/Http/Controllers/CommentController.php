<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Comment;
use App\Models\CommentLike;
use App\Notifications\CommentActivityNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'announcement_id' => 'required|exists:announcements,id',
            'parent_id' => 'nullable|exists:comments,id',
            'content' => 'required|string|max:5000',
        ]);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'announcement_id' => $request->announcement_id,
            'parent_id' => $request->parent_id ?: null,
            'content' => $request->content,
        ]);

        // Notify announcement owner and/or parent comment owner (excluding actor)
        $actorId = Auth::id();
        $announcement = Announcement::find($request->announcement_id);
        if ($announcement) {
            // Notify post author for new top-level comment (or any comment not by them)
            if ($announcement->posted_by && $announcement->posted_by !== $actorId) {
                $announcement->user?->notify(new CommentActivityNotification(
                    activityType: 'comment',
                    announcement: $announcement,
                    comment: $comment,
                    actorUserId: $actorId
                ));
            }
        }
        if ($comment->parent_id) {
            $parent = Comment::with('user')->find($comment->parent_id);
            if ($parent && $parent->user_id && $parent->user_id !== $actorId) {
                $parent->user->notify(new CommentActivityNotification(
                    activityType: 'reply',
                    announcement: $announcement ?? $comment->announcement,
                    comment: $comment,
                    actorUserId: $actorId
                ));
            }
        }

        return back()->with('success', 'Comment posted.');
    }

    public function reply(Request $request, Comment $comment)
    {
        $request->validate([
            'content' => 'required|string|max:5000',
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'announcement_id' => $comment->announcement_id,
            'parent_id' => $comment->id,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Reply posted.');
    }

    public function like(Comment $comment)
    {
        $userId = Auth::id();
        $existing = CommentLike::where('comment_id', $comment->id)->where('user_id', $userId)->first();
        if ($existing) {
            $existing->delete();
            return back()->with('success', 'Unliked comment.');
        }
        CommentLike::create([
            'comment_id' => $comment->id,
            'user_id' => $userId,
        ]);
        return back()->with('success', 'Liked comment.');
    }

    public function edit(Comment $comment)
    {
        $this->authorizeOwner($comment);
        return view('partials.edit_comment', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorizeOwner($comment);
        $request->validate([
            'content' => 'required|string|max:5000',
        ]);
        $comment->update([
            'content' => $request->content,
            'edited_at' => now(),
        ]);
        return back()->with('success', 'Comment updated.');
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $this->authorizeOwner($comment);
        $comment->delete();
        return back()->with('success', 'Comment deleted.');
    }

    protected function authorizeOwner(Comment $comment): void
    {
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
    }
}


