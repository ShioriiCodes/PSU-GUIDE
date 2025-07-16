<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Announcement;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

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
            'user_id' => auth()->id(),
            'announcement_id' => $request->announcement_id,
            'parent_id' => $request->parent_id,
            'content' => $request->content, // ✅ THIS LINE IS CRITICAL
        ]);

        return back()->with('success', 'Comment added!');
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        if ($comment->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate(['content' => 'required|string|max:2000']);
        $comment->update(['content' => $request->content]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'updated_comment',
            'target_type' => 'App\Models\Comment',
            'target_id' => $comment->id,
            'timestamp' => now(),
        ]);

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
            'action' => 'deleted_comment',
            'target_type' => 'App\Models\Comment',
            'target_id' => $comment->id,
            'timestamp' => now(),
        ]);

        return back()->with('success', 'Comment deleted.');
    }


}


