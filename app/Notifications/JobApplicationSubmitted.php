<?php

namespace App\Notifications;

use App\Models\JobApplication;
use Illuminate\Notifications\Notification;

class JobApplicationSubmitted extends Notification
{
    public function __construct(public JobApplication $application) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => '📄 Lamaran baru dari ' . $this->application->full_name,
            'body'  => 'Melamar: ' . ($this->application->job->title ?? 'Lowongan') . ' di ' . ($this->application->job->company->company_name ?? '-'),
            'link'  => route('admin.job-applications.index'),
            'type'  => 'job_application',
        ];
    }
}
