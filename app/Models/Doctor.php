<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [
            'name',
            'address',
            'phone_number',
            'specialist',
            'gender',
            'date_of_birth',
            'status',
            'user_id',
        ];

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

}
