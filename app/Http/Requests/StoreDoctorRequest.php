<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:' . User::class . ',email'],
            'password' => ['required', 'string', 'min:8'],
            'license_number' => ['required', 'string', 'max:50', 'unique:doctors,license_number', 'regex:/^[A-Z]{3}-\d{7}$/'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'consultation_fee' => ['required', 'numeric', 'min:0', 'max:10000'],
            'room' => ['nullable', 'string', 'max:20'],
            'specializations' => ['required', 'array', 'min:1'],
            'specializations.*' => ['exists:specializations,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'license_number.regex' => 'Numer licencji musi mieć format: ABC-1234567 (3 wielkie litery, myślnik, 7 cyfr).',
            'consultation_fee.min' => 'Cena konsultacji nie może być mniejsza niż 0.',
            'specializations.required' => 'Wybierz co najmniej jedną specjalizację.',
            'email.unique' => 'Ten e-mail jest już zajęty.',
        ];
    }
}
