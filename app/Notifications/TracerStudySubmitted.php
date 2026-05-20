<?php

namespace App\Notifications;

use App\Models\TracerStudy;
use Illuminate\Notifications\Notification;

class TracerStudySubmitted extends Notification
{
    public function __construct(public TracerStudy $tracer) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => '📊 Tracer study diisi oleh ' . ($this->tracer->student->full_name ?? 'Alumni'),
            'body'  => 'Status: ' . $this->tracer->status_saat_ini,
            'link'  => route('admin.tracer.index'),
            'type'  => 'tracer_study',
        ];
    }
}
