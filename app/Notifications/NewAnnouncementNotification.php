<?php

namespace App\Notifications;

use App\Models\Announcement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAnnouncementNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Announcement $announcement
    ) {
    }

    public function via(object $notifiable): array
    {
        // Store in database; optionally you can add 'mail'
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_announcement',
            'announcement_id' => $this->announcement->id,
            'title' => $this->announcement->title,
            'message' => 'A new announcement has been posted.',
            'url' => route('announcements.show', $this->announcement->id),
        ];
    }
}


