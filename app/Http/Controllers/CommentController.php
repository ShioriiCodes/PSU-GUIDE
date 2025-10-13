<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Announcement;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use App\Notifications\CommentReplyNotification;

class CommentController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'announcement_id' => 'required|exists:announcements,id',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'announcement_id' => $request->announcement_id,
            'parent_id' => $request->parent_id,
            'content' => $request->content,
        ]);

        // 🔔 If it's a reply to someone else, notify the original commenter
        if ($comment->parent_id) {
            $parent = Comment::find($comment->parent_id);
            if ($parent && $parent->user_id !== Auth::id()) {
                $parent->user->notify(new CommentReplyNotification($comment));
            }
        }

        return back()->with('success', 'Comment added!');
    }

    public function edit(Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            abort(403);
        }

        return response()->json(['content' => $comment->content]);
    }

    public function update(Request $request, Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $comment->update([
            'content' => $request->content,
            'edited_at' => now(),
        ]);

        return back()->with('success', 'Comment updated.');
    }

    public function like(Comment $comment)
    {
        $user = Auth::user();

        if ($comment->isLikedBy($user)) {
            $comment->likes()->detach($user->id);
        } else {
            $comment->likes()->attach($user->id);
        }

        return back();
    }

    public function reply(Request $request, Comment $comment)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $reply = Comment::create([
            'user_id' => Auth::id(),
            'announcement_id' => $comment->announcement_id,
            'parent_id' => $comment->id,
            'content' => $request->content,
        ]);

        // Notify the parent comment author
        if ($comment->user_id !== Auth::id()) {
            $comment->user->notify(new CommentReplyNotification($reply));
        }

        return back()->with('success', 'Reply added!');
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        if ($comment->user_id !== Auth::id()) {
            abort(403);
        }

        $comment->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'deleted_comment',
            'target_type' => 'comment',
            'target_id' => $comment->id,
            'timestamp' => now(),
        ]);

        return back()->with('success', 'Comment deleted.');
    }

    public function loadFull($id)
    {
        $announcement = Announcement::with([
            'user',
            'category',
            'comments.user',
            'comments.replies.user'
        ])->findOrFail($id);

        return view('partials.full_post_modal', compact('announcement'));
    }

}


