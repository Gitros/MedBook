<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\ValidPesel;
use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
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
            'pesel' => ['required', new ValidPesel(), 'unique:patients,pesel'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9 +\-]+$/'],
            'birth_date' => ['required', 'date', 'before:today', 'after:1900-01-01'],
            'address' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'pesel.digits' => 'PESEL musi mieć dokładnie 11 cyfr.',
            'pesel.unique' => 'Pacjent o tym PESEL już istnieje.',
            'phone.regex' => 'Telefon może zawierać tylko cyfry, spacje, + i -.',
            'birth_date.before' => 'Data urodzenia musi być w przeszłości.',
            'email.unique' => 'Ten e-mail jest już zajęty.',
        ];
    }
}
