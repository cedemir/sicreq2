<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TituloRequest extends FormRequest
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
            'titulo_descricao' => ['required', 'string', 'max:50'],
            'editora_codigo' => ['required', 'integer', Rule::exists('editora', 'editora_codigo')],
            'tipotitulo_codigo' => ['required', 'integer', Rule::exists('tipo_titulo', 'tipotitulo_codigo')],
            'situacao_codigo' => ['required', 'integer', Rule::exists('situacao_titulo', 'situacao_codigo')],
            'titulo_periodicidade' => ['nullable', 'integer', 'min:0'],
            'titulo_origem' => ['nullable', 'string', 'max:50'],
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
            'titulo_descricao' => 'nome',
            'editora_codigo' => 'editora',
            'tipotitulo_codigo' => 'tipo',
            'situacao_codigo' => 'situação',
            'titulo_periodicidade' => 'periodicidade',
            'titulo_origem' => 'origem',
        ];
    }
}
