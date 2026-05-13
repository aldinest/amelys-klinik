<?php

namespace App\Notifications;
               
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservasiNotification extends Notification
{
    use Queueable;

    protected $reservation;
    /**
     * Create a new notification instance.
     */
    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    // Tentukan mau dikirim ke mana (kita pilih database dulu)
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    // Data yang akan disimpan ke kolom 'data' di tabel notifications
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
    // Data yang akan disimpan ke kolom 'data' di tabel notifications
    public function toArray($notifiable)
    {
        // Pastikan relasi 'patient' dan 'doctorSchedule->doctor' sudah ter-load
        return [
            'id_reservasi' => $this->reservation->id,
            'nama_pasien'  => $this->reservation->patient->name, // Ambil name dari relasi patient
            'pesan'        => 'Ada reservasi baru: ' . $this->reservation->patient->name . 
                              ' untuk dokter ' . $this->reservation->doctorSchedule->doctor->name,
            'action_url'   => route('pengurus.reservations.show', $this->reservation->doctor_schedule_id),
            'waktu'        => now()->format('H:i d-m-Y'),
        ];
    }
}
