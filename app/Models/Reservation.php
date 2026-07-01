<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'doctor_schedule_id',
        'patient_id',
        'action',
        'status',
    ];

    // SCOPE BARU: Untuk memudahkan pengecekan kuota di seluruh aplikasi
    public function scopeBooked($query)
    {
        return $query->whereIn('status', ['approved', 'completed']);
    }
    
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function doctorSchedule()
    {
        return $this->belongsTo(DoctorSchedule::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function schedule()
    {
        return $this->belongsTo(DoctorSchedule::class, 'doctor_schedule_id');
    }

    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }

    public function actions()
    {
        return $this->hasMany(ReservationAction::class);
    }


}
