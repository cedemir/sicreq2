<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExemplarRequest extends FormRequest
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
            'titulo_codigo' => ['required', 'integer', Rule::exists('titulo', 'titulo_codigo')],
            'exemplar_codigo' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('exemplar', 'exemplar_codigo')
                    ->where('titulo_codigo', $this->integer('titulo_codigo'))
                    ->ignore($this->route('exemplar')),
            ],
            'exemplar_subtitulo' => ['nullable', 'string', 'max:255'],
            'exemplar_capa' => ['nullable', 'image', 'max:2048'],
            'exemplar_data' => ['nullable', 'date'],
            'armazenagem_codigo' => ['nullable', 'integer', Rule::exists('armazenagem', 'armazenagem_codigo')],
            'tamanho_codigo' => ['nullable', 'integer', Rule::exists('tamanho', 'tamanho_codigo')],
            'exemplar_situacao' => ['nullable', 'integer', 'min:0', 'max:32767'],
            'exemplar_numeropagina' => ['nullable', 'integer', 'min:0'],
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
            'titulo_codigo' => 'título',
            'exemplar_codigo' => 'número',
            'exemplar_subtitulo' => 'subtítulo',
            'exemplar_capa' => 'capa',
            'exemplar_data' => 'data',
            'armazenagem_codigo' => 'armazenagem',
            'tamanho_codigo' => 'tamanho',
            'exemplar_situacao' => 'situação',
            'exemplar_numeropagina' => 'nº de páginas',
        ];
    }
}
