<?php

namespace App\Http\Requests;

use App\Models\Appointment;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $u = $this->user();
        return $u && ($u->isPatient() || $u->isAdmin());
    }

    public function rules(): array
    {
        return [
            'doctor_id' => ['required', 'exists:doctors,id'],
            'specialization_id' => ['nullable', 'exists:specializations,id'],
            'appointment_date' => ['required', 'date', 'after:now'],
            'reason' => ['required', 'string', 'min:5', 'max:500'],
            'patient_id' => ['sometimes', 'exists:patients,id'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($v) {
            $date = $this->input('appointment_date');
            $doctorId = $this->input('doctor_id');
            if ($date && $doctorId) {
                $exists = Appointment::where('doctor_id', $doctorId)
                    ->where('appointment_date', $date)
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->exists();
                if ($exists) {
                    $v->errors()->add('appointment_date', 'Ten termin jest już zajęty u wybranego lekarza.');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'appointment_date.after' => 'Termin musi być w przyszłości.',
            'reason.min' => 'Powód wizyty musi mieć min. 5 znaków.',
            'doctor_id.required' => 'Wybierz lekarza.',
        ];
    }
}
