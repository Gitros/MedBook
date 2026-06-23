<?php

namespace App\Http\Requests;

use App\Rules\ValidPesel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        if (!$user) return false;
        if ($user->isAdmin()) return true;
        // pacjent może edytować tylko swoje
        $patient = $this->route('patient');
        return $user->isPatient() && $patient && $patient->user_id === $user->id;
    }

    public function rules(): array
    {
        $patient = $this->route('patient');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($patient->user_id)],
            'pesel' => ['required', new ValidPesel(), Rule::unique('patients', 'pesel')->ignore($patient->id)],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9 +\-]+$/'],
            'birth_date' => ['required', 'date', 'before:today', 'after:1900-01-01'],
            'address' => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'pesel.digits' => 'PESEL musi mieć dokładnie 11 cyfr.',
            'pesel.unique' => 'Pacjent o tym PESEL już istnieje.',
            'phone.regex' => 'Telefon może zawierać tylko cyfry, spacje, + i -.',
            'birth_date.before' => 'Data urodzenia musi być w przeszłości.',
        ];
    }
}
