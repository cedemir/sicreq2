<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ImagemPersonagemRequest extends FormRequest
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
            'imagem_codigo' => ['nullable', 'integer', 'min:1'],
            'imagem_descricao' => ['nullable', 'string', 'max:150'],
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
            'imagem_codigo' => 'código da imagem',
            'imagem_descricao' => 'descrição',
        ];
    }
}
