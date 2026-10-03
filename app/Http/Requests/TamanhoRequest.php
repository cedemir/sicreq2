<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TamanhoRequest extends FormRequest
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
            'tamanho_descricao' => ['required', 'string', 'max:255'],
            'tamanho_linha' => ['nullable', 'numeric', 'min:0'],
            'tamanho_coluna' => ['nullable', 'numeric', 'min:0'],
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
            'tamanho_descricao' => 'descrição',
            'tamanho_linha' => 'linha',
            'tamanho_coluna' => 'coluna',
        ];
    }
}
