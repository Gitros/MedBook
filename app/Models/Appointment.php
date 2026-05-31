<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['doctor_id', 'patient_id', 'specialization_id', 'appointment_date', 'status', 'reason', 'notes'];

    protected $casts = [
        'appointment_date' => 'datetime',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }
}
