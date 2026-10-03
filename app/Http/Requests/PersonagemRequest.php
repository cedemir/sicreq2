<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PersonagemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'personagem_descricao' => ['required', 'string', 'max:50'],
            'universo_codigo' => ['required', 'integer', Rule::exists('universo', 'universo_codigo')],
            'personagem_origem' => ['nullable', 'string', 'max:16000'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'personagem_descricao' => 'nome',
            'universo_codigo' => 'universo',
            'personagem_origem' => 'origem',
        ];
    }
}
