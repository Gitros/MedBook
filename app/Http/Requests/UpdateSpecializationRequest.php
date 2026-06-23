<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSpecializationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        $id = $this->route('specialization')->id ?? null;

        return [
            'name' => ['required', 'string', 'min:3', 'max:100', Rule::unique('specializations', 'name')->ignore($id)],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nazwa jest wymagana.',
            'name.min' => 'Nazwa musi mieć minimum 3 znaki.',
            'name.unique' => 'Specjalizacja o tej nazwie już istnieje.',
        ];
    }
}
