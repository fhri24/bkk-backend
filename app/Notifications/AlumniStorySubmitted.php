<?php

namespace App\Notifications;

use App\Models\AlumniStory;
use Illuminate\Notifications\Notification;

class AlumniStorySubmitted extends Notification
{
    public function __construct(public AlumniStory $story) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => '📖 Kisah sukses baru dari ' . $this->story->name,
            'body'  => $this->story->job_title . ' — menunggu persetujuan.',
            'link'  => route('admin.alumni-stories.index'),
            'type'  => 'alumni_story',
        ];
    }
}
