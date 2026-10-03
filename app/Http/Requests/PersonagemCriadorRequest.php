<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PersonagemCriadorRequest extends FormRequest
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
            'personagem_codigo' => ['required', 'integer', Rule::exists('personagem', 'personagem_codigo')],
            'artista_codigo' => [
                'required',
                'integer',
                Rule::exists('artista', 'artista_codigo'),
                Rule::unique('personagem_criador', 'artista_codigo')
                    ->where('personagem_codigo', $this->integer('personagem_codigo'))
                    ->ignore($this->route('personagem_criador')),
            ],
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
            'personagem_codigo' => 'personagem',
            'artista_codigo' => 'artista',
        ];
    }
}
