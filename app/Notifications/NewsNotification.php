<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\News;

class NewsNotification extends Notification
{
    use Queueable;

    protected $news;
    protected $action;

    public function __construct(News $news, string $action = 'diterbitkan')
    {
        $this->news = $news;
        $this->action = $action;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $actionUrl = null;

        if (method_exists($notifiable, 'isAdmin') && $notifiable->isAdmin()) {
            $actionUrl = route('admin.news.index');
        }

        return [
            'title'      => $this->news->title,
            'pesan'      => "Info baru telah {$this->action}: {$this->news->title}",
            'action_url' => $actionUrl,
            'waktu'      => now()->format('H:i d-m-Y'),
        ];
    }
}
