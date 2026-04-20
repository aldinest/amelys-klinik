<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorDisplaySchedule extends Model
{
    protected $fillable = [
        'doctor_name',
        'specialty',
        'day',
        'start_time',
        'end_time',
        'is_active',
    ];
}
