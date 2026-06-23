<?php

namespace App\Http\Requests;

use App\Models\Appointment;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $u = $this->user();
        if (!$u) return false;
        $appt = $this->route('appointment');
        if ($u->isAdmin()) return true;
        if ($u->isDoctor() && $appt->doctor->user_id === $u->id) return true;
        if ($u->isPatient() && $appt->patient->user_id === $u->id) return true;
        return false;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:pending,confirmed,completed,cancelled'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'appointment_date' => ['sometimes', 'required', 'date', 'after:now'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($v) {
            if (!$this->filled('appointment_date')) return;
            $appt = $this->route('appointment');
            $exists = Appointment::where('doctor_id', $appt->doctor_id)
                ->where('id', '!=', $appt->id)
                ->where('appointment_date', $this->input('appointment_date'))
                ->whereIn('status', ['pending', 'confirmed'])
                ->exists();
            if ($exists) {
                $v->errors()->add('appointment_date', 'Termin zajęty.');
            }
        });
    }
}
