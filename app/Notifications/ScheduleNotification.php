<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\DoctorSchedule;

class ScheduleNotification extends Notification
{
    use Queueable;

    protected $schedule;
    protected $action;

    public function __construct(DoctorSchedule $schedule, string $action = 'ditambahkan')
    {
        $this->schedule = $schedule;
        $this->action = $action;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $actionUrl = route('welcome');

        if (method_exists($notifiable, 'isPasien') && $notifiable->isPasien()) {
            $actionUrl = route('pasien.reservations.index');
        } elseif (method_exists($notifiable, 'isPengurus') && $notifiable->isPengurus()) {
            $actionUrl = route('pengurus.doctor_schedules.index');
        }

        return [
            'title'      => 'Perubahan Jadwal Dokter',
            'pesan'      => "Jadwal dr. {$this->schedule->doctor->name} pada {$this->schedule->schedule_date} telah {$this->action}.",
            'action_url' => $actionUrl,
            'waktu'      => now()->format('H:i d-m-Y'),
        ];
    }
}
