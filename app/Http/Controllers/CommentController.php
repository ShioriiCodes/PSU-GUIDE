<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Announcement;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use App\Notifications\CommentRepliedNotification;

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
                $parent->user->notify(new CommentRepliedNotification($comment));
            }
        }

        return back()->with('success', 'Comment added!');
    }

        public function update(Request $request, $id)
        {
            $comment = Comment::findOrFail($id);

            if ($comment->user_id !== Auth::id()) {
                abort(403);
            }

            $request->validate([
                'content' => 'required|string|max:2000',
            ]);

            $comment->update(['content' => $request->content]);

            return back()->with('success', 'Comment updated.');
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
            'action' => 'updated_comment',
            'target_type' => 'App\Models\Comment',
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


