<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        $doctor = $this->route('doctor');
        $userId = $doctor->user_id ?? null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'license_number' => ['required', 'string', 'max:50', 'regex:/^[A-Z]{3}-\d{7}$/', Rule::unique('doctors', 'license_number')->ignore($doctor->id ?? null)],
            'bio' => ['nullable', 'string', 'max:2000'],
            'consultation_fee' => ['required', 'numeric', 'min:0', 'max:10000'],
            'room' => ['nullable', 'string', 'max:20'],
            'is_active' => ['sometimes', 'boolean'],
            'specializations' => ['required', 'array', 'min:1'],
            'specializations.*' => ['exists:specializations,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'license_number.regex' => 'Numer licencji musi mieć format: ABC-1234567.',
            'consultation_fee.min' => 'Cena konsultacji nie może być mniejsza niż 0.',
            'specializations.required' => 'Wybierz co najmniej jedną specjalizację.',
        ];
    }
}
