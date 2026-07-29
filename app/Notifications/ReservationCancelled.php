<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationCancelled extends Notification
{
    use Queueable;

    // 1. Deklarasikan property details
    protected $details;

    /**
     * Create a new notification instance.
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $reason = $this->details['alasan'] ?? 'Tidak ada alasan tertera.';

        return [
            'title' => 'Reservasi Dibatalkan',
            'pesan' => 'Reservasi Anda pada ' . $this->details['tanggal'] . ' telah dibatalkan. Alasan: ' . $reason,
            'alasan' => $reason,
            'action_url' => url('/pasien/reservations'),
            'waktu' => now()->format('H:i d-m-Y'),
        ];
    }
}
