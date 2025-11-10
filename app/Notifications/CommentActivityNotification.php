<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Announcement;
use App\Models\Comment;

class CommentActivityNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly string $activityType, // 'comment' | 'reply' | 'mention'
        public readonly Announcement $announcement,
        public readonly Comment $comment,
        public readonly ?int $actorUserId = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): DatabaseMessage
    {
        $title = $this->activityType === 'reply' ? 'New reply' : 'New comment';
        $message = $this->activityType === 'reply'
            ? 'Someone replied to your comment.'
            : 'Someone commented on your post.';

        $url = route('announcement', [
            'modal' => 'comments',
            'announcement_id' => $this->announcement->id,
            'comment_id' => $this->comment->id,
        ]);

        return new DatabaseMessage([
            'title' => $title,
            'message' => $message,
            'announcement_id' => $this->announcement->id,
            'comment_id' => $this->comment->id,
            'actor_user_id' => $this->actorUserId,
            'url' => $url,
        ]);
    }
}


