<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
     protected $fillable = [
        'doctor_id',
        'schedule_date',
        'start_time',
        'end_time',
        'quota',
        'status'
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    // public function remainingQuota()
    // {
    //     return $this->quota - $this->reservations()->count();
    // }

    public function approvedReservations()
    {
        return $this->hasMany(Reservation::class)
            ->where('status', 'approved');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function remainingQuota()
    {
        return $this->quota - $this->reservations()
            ->where('status', 'approved')
            ->count();
    }

}
