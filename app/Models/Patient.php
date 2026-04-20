<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'medical_record_number',
        'name',
        'address',
        'phone',
        'gender',
        'date_of_birth',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'patient_id');
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }
    
}
