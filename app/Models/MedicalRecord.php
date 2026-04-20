<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'reservation_id',
        'examined_at',
        'complaint',
        'diagnosis',
        'treatment',
        'doctor_notes',
    ];

    /**
     * One medical record belongs to one patient
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * One medical record handled by one doctor
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Medical record may belong to a reservation
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

}
