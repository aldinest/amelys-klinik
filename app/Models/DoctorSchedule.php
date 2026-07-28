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
        // Jika masih dipakai di tempat lain, biarkan saja
        return $this->hasMany(Reservation::class)->where('status', 'approved');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    // Biarkan fungsi lama ada, tapi ubah isinya agar sinkron
    public function remainingQuota()
    {
        // Mengalihkan ke logika baru yang sudah terpusat
        return $this->remaining_quota; 
    }

    // Gunakan ini sebagai satu-satunya standar penghitungan sisa kuota
    public function getRemainingQuotaAttribute() 
    {
        // Memanggil scopeCountingQuota dari model Reservation
        // Ini menjamin perhitungan selalu sinkron dengan definisi status valid Anda
        $used = $this->reservations()->countingQuota()->count();
                        
        return ($this->quota ?? 5) - $used;
    }


}
